<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessReview;
use App\Models\LocalBusiness;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Helpers\NotificationHelper;

class BusinessReviewController extends Controller
{
    public function store(Request $request, $businessId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $user = Auth::user();
        $business = LocalBusiness::findOrFail($businessId);

        // Verify that the user has a delivered order from this business
        $hasDeliveredOrder = Order::where('local_business_id', $business->id)
            ->where('user_id', $user->id)
            ->where('status', 'delivered')
            ->exists();

        if (!$hasDeliveredOrder && $user->role !== 'admin') {
            return back()->with('error', 'Solo puedes calificar negocios en los que hayas realizado un pedido y este haya sido entregado.');
        }

        // Check if user already reviewed
        $existingReview = BusinessReview::where('local_business_id', $business->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingReview) {
            $existingReview->update([
                'rating' => $request->rating,
                'comment' => $request->comment
            ]);
            $message = 'Reseña actualizada con éxito.';
        } else {
            $review = BusinessReview::create([
                'local_business_id' => $business->id,
                'user_id' => $user->id,
                'rating' => $request->rating,
                'comment' => $request->comment
            ]);
            $message = 'Reseña enviada con éxito.';

            // Notificar al dueño del negocio
            if ($business->user) {
                NotificationHelper::newBusinessReview($business->user, $review);
            }
        }

        return back()->with('success', $message);
    }
}
