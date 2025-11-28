<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExportSQLiteData extends Command
{
    protected $signature = 'db:export-sqlite';
    protected $description = 'Exporta datos de SQLite a archivos JSON';

    public function handle()
    {
        // Configurar conexión temporal a SQLite
        config(['database.connections.sqlite_old' => [
            'driver' => 'sqlite',
            'database' => database_path('database.sqlite'),
        ]]);

        $tables = DB::connection('sqlite_old')
            ->select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");

        if (!is_dir(storage_path('app/exports'))) {
            mkdir(storage_path('app/exports'), 0755, true);
        }

        foreach ($tables as $table) {
            $tableName = $table->name;
            $data = DB::connection('sqlite_old')->table($tableName)->get()->toArray();

            $filename = storage_path("app/exports/{$tableName}.json");
            file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));

            $this->info("Exportada tabla: {$tableName} (" . count($data) . " registros)");
        }

        $this->info('Exportación completada!');
        return 0;
    }
}
