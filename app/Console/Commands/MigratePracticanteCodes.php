<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Models\Practicante;

class MigratePracticanteCodes extends Command
{
    protected $signature = 'practicantes:migrate-codes';
    protected $description = 'Migra los códigos de practicantes al nuevo formato secuencial';

    public function handle()
    {
        DB::beginTransaction();

        try {
            $practicantes = Practicante::orderBy('id_practicante')->get();
            $prefix = 'A';
            $counter = 1;

            foreach ($practicantes as $practicante) {
                $newCode = $prefix . str_pad($counter, 3, '0', STR_PAD_LEFT);
                
                $this->info("Actualizando practicante ID {$practicante->id_practicante} de {$practicante->codigo} a {$newCode}");
                
                // Solo necesitamos actualizar el código del practicante
                // No hay que actualizar bitácora porque usa practicante_id
                $practicante->codigo = $newCode;
                $practicante->save();
                
                $counter++;
                
                if ($counter > 999) {
                    $prefix = chr(ord($prefix) + 1);
                    $counter = 1;
                }
            }

            DB::commit();
            $this->info('¡Migración de códigos completada!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error durante la migración: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}