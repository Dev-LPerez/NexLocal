<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * (Turista) Crear un nuevo pedido.
     */
    public function store(StoreOrderRequest $request)
    {
        $user = Auth::user();
        $items = $request->validated()['items'];

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $orderItemsData = [];
            $localBusinessId = null;

            // Procesar cada ítem para calcular el total consultando los precios en la BD
            foreach ($items as $itemData) {
                // Usar lockForUpdate para evitar race conditions
                $product = Product::lockForUpdate()->findOrFail($itemData['product_id']);
                
                $quantity = $itemData['quantity'];

                // Verificar stock
                if (!$product->hasStock($quantity)) {
                    throw new \Exception("Stock insuficiente para '{$product->name}'.");
                }

                // Si es el primer producto, establecemos el negocio del pedido
                if ($localBusinessId === null) {
                    $localBusinessId = $product->local_business_id;
                } else if ($localBusinessId !== $product->local_business_id) {
                    // Opcional: Validación para evitar productos de distintos negocios en un solo pedido
                    throw new \Exception('No puedes agregar productos de distintos emprendimientos en un mismo pedido.');
                }

                $unitPrice = $product->price;
                $totalAmount += ($unitPrice * $quantity);

                // Descontar stock
                if ($product->stock !== null) {
                    $product->decrement('stock', $quantity);
                    if ($product->fresh()->stock === 0) {
                        $product->update(['is_available' => false]);
                    }
                }

                // Preparar los datos del ítem para insertarlos luego
                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                ];
            }

            // Crear el registro principal del pedido (Order)
            $order = Order::create([
                'user_id' => $user->id,
                'local_business_id' => $localBusinessId,
                'total_amount' => $totalAmount,
                'status' => 'pending', // Estado por defecto
            ]);

            // Crear los registros de los ítems del pedido (OrderItem)
            foreach ($orderItemsData as $itemData) {
                $order->items()->create($itemData);
            }

            DB::commit();

            // Retornar el pedido con sus ítems para confirmación
            $order->load('items.product', 'localBusiness');

            return response()->json([
                'success' => true,
                'message' => 'Pedido creado exitosamente.',
                'data' => $order
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el pedido: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * (Turista) Historial de pedidos del usuario autenticado.
     */
    public function indexTourist()
    {
        $user = Auth::user();

        // Obtener los pedidos que ha hecho el usuario, con info del negocio y los ítems
        $orders = $user->orders()->with('localBusiness', 'items.product')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * (Propietario) Pedidos que han entrado al negocio del usuario.
     */
    public function indexOwner()
    {
        $user = Auth::user();
        $business = $user->localBusiness;

        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes un emprendimiento registrado.'
            ], 404);
        }

        // Obtener todos los pedidos dirigidos al negocio
        $orders = $business->orders()->with('user', 'items.product')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * (Propietario) Cambiar el estado de un pedido.
     */
    public function updateStatus(UpdateOrderStatusRequest $request, $id)
    {
        $user = Auth::user();
        $business = $user->localBusiness;

        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes un emprendimiento registrado.'
            ], 404);
        }

        $order = $business->orders()->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido no encontrado en tu emprendimiento.'
            ], 404);
        }

        $newStatus = $request->validated()['status'];

        try {
            DB::beginTransaction();

            if ($newStatus === 'cancelled' && $order->status !== 'cancelled') {
                // Reponer stock
                foreach ($order->items as $item) {
                    $product = $item->product;
                    if ($product && $product->stock !== null) {
                        $product->increment('stock', $item->quantity);
                        if (!$product->is_available && $product->stock > 0) {
                            $product->update(['is_available' => true]);
                        }
                    }
                }
            }

            $order->update([
                'status' => $newStatus
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Estado del pedido actualizado exitosamente.',
                'data' => $order
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado: ' . $e->getMessage()
            ], 500);
        }
    }
}
