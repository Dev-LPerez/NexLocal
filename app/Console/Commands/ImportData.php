<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportData extends Command
{
    protected $signature = 'db:import-data';
    protected $description = 'Importa datos desde archivos JSON a PostgreSQL';

    public function handle()
    {
        // Tablas que NO se deben importar (Laravel las maneja automáticamente)
        $excludedTables = ['migrations'];

        // Orden de importación respetando las llaves foráneas
        // Solo incluir tablas que realmente se exportaron
        $orderedTables = [
            'users',
            'password_reset_tokens',
            'sessions',
            'cache',
            'cache_locks',
            'jobs',
            'job_batches',
            'failed_jobs',
            'experiences',
            'availability_slots',
            'bookings',
            'reviews',
            'chat_messages',
            'notifications',
        ];

        $files = glob(storage_path('app/exports/*.json'));
        $filesByTable = [];

        foreach ($files as $file) {
            $tableName = basename($file, '.json');

            // Excluir tablas del sistema
            if (in_array($tableName, $excludedTables)) {
                $this->warn("Omitiendo tabla del sistema: {$tableName}");
                continue;
            }

            $filesByTable[$tableName] = $file;
        }

        // Deshabilitar verificación de llaves foráneas temporalmente
        DB::statement('SET CONSTRAINTS ALL DEFERRED');

        // Importar en orden
        foreach ($orderedTables as $tableName) {
            if (!isset($filesByTable[$tableName])) {
                continue;
            }

            $file = $filesByTable[$tableName];
            $data = json_decode(file_get_contents($file), true);

            if (!empty($data)) {
                $data = json_decode(json_encode($data), true);

                try {
                    DB::table($tableName)->insert($data);
                    $this->info("✓ Importada tabla: {$tableName} (" . count($data) . " registros)");
                } catch (\Exception $e) {
                    $this->error("✗ Error importando {$tableName}: " . $e->getMessage());
                }
            } else {
                $this->warn("⚠ Tabla vacía: {$tableName}");
            }
        }

        // Importar tablas restantes
        foreach ($filesByTable as $tableName => $file) {
            if (in_array($tableName, $orderedTables)) {
                continue;
            }

            $data = json_decode(file_get_contents($file), true);

            if (!empty($data)) {
                $data = json_decode(json_encode($data), true);

                try {
                    DB::table($tableName)->insert($data);
                    $this->info("✓ Importada tabla adicional: {$tableName} (" . count($data) . " registros)");
                } catch (\Exception $e) {
                    $this->error("✗ Error importando {$tableName}: " . $e->getMessage());
                }
            }
        }

        $this->info('🎉 Importación completada!');
        return 0;
    }
}
