<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LlamadasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Definir la cantidad de registros que quieres insertar
        $cantidad = 1000000;

        // Insertar registros directamente en la tabla llamadas
        for ($i = 0; $i < $cantidad; $i++) {
            DB::table('llamadas')->insert([
                'llamada_correlativo' => $this->generateCorrelativo(), // Correlativo generado con formato
                'medico_aph' => fake()->name(),
                'hora_creacion' => fake()->dateTimeBetween('-1 week', 'now'),
                'telefono_alertante' => fake()->phoneNumber(),
                'nombre_alertante' => fake()->name(),
                'motivo_literal' => fake()->sentence(),
                'tipo_caso' => fake()->randomElement(['Emergencia', 'Consulta', 'Otro']),
                'descripcion_caso' => fake()->paragraph(),
                'lugar_origen' => fake()->address(),
                'lugar_destino' => fake()->address(),
                'cod_ambulancia' => fake()->bothify('AMB-###'),
                'opcion_pertinente' => fake()->boolean(),
                'opcion_informativa' => fake()->boolean(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }

    /**
     * Genera un correlativo único para cada llamada con el formato
     * ddmmyyhhmmss + Llamada + número secuencial.
     *
     * @return string
     */
    private function generateCorrelativo()
    {
        $fecha = now()->format('dmYHis');
        $tipoCasoInicial = strtoupper(substr('Llamada', 0, 2));
        $contador = DB::table('llamadas')->whereDate('created_at', now()->format('Y-m-d'))->count();
        $numeroSecuencial = str_pad($contador + 1, 5, '0', STR_PAD_LEFT); // Aseguramos que el número tenga 5 dígitos
        return "{$fecha}{$tipoCasoInicial}{$numeroSecuencial}";
    }
}
