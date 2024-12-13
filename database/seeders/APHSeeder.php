<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class APHSeeder extends Seeder
{

    public function run()
    {
        $cantidad = 200000;

        for ($i = 0; $i < $cantidad; $i++) {
            print($i.',');
            DB::table('casos')->insert([
                // GENERAL
                'usuario' => fake()->userName(),
                'llamada_asociada' => fake()->numberBetween(1, 1000),
                'correlativo_caso' => $this->generateCorrelativo(),
                'tipo_caso' => fake()->randomElement(['Asistencia PH']),
                'llamada_id' => fake()->numberBetween(200, 1000),

                // OPERADOR
                'nombres_paciente' => fake()->firstName(),
                'apellidos_paciente' => fake()->lastName(),
                'edad' => fake()->numberBetween(1, 100),
                'edad_complemento' => fake()->randomElement(['A', 'M', 'D']),
                'sexo' => fake()->randomElement(['M', 'F']),
                'dirección' => fake()->address(),
                'puntos_referencia' => fake()->sentence(),
                'departamento' => fake()->randomElement(['La Paz']),
                'distrito' => fake()->randomElement(['Olocuilta']),
                'tap' => fake()->word(),
                'tap1' => fake()->word(),
                'plan_experto' => fake()->sentence(),
                'prioridad' => fake()->randomElement(['1', '2', '3','4']),
                'antecedentes' => fake()->paragraph(),
                'enfermedades' => fake()->sentence(),
                'asegurado' => fake()->boolean(),
                'institucion' => fake()->company(),
                'institucion_apoyo' => fake()->company(),
                'notas' => fake()->paragraph(),

                // GESTOR
                'via_transporte' => fake()->randomElement(['Terrestre', 'Acuatico','Aereo']),
                'tipo_unidad' => fake()->randomElement(['A', 'B','C']),
                'recurso_asignado' => fake()->bothify('U-###'),
                'notas_gestor_recurso' => fake()->paragraph(),
                'unidad_salud_traslado' => fake()->company(),
                'unidad_salud_sugerido' => fake()->company(),

                'efectividad' => fake()->randomElement(['Si', 'No']),
                'razon_noefectivo' => fake()->sentence(),
                'exclusion' => fake()->randomElement(['Si', 'No']),
                'motivo_exclusion' => fake()->sentence(),
                'notas_gestor' => fake()->paragraph(),

                // MEDICO
                'cie10' => fake()->randomElement(['A01', 'B02', 'C03']),
                'juicio_clinico1' => fake()->sentence(),
                'juicio_clinico2' => fake()->sentence(),
                'juicio_clinico3' => fake()->sentence(),
                'notas_medicos' => fake()->paragraph(),

                'color' => fake()->safeHexColor(),

                // Timestamps
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }

    private function generateCorrelativo()
    {
        $fecha = now()->format('dmYHis');
        $tipoCasoInicial = strtoupper(substr('Llamada', 0, 2));
        $contador = DB::table('casos')->whereDate('created_at', now()->format('Y-m-d'))->count();
        $numeroSecuencial = str_pad($contador + 1, 6, '0', STR_PAD_LEFT); // Aseguramos que el número tenga 5 dígitos
        return "{$fecha}{$tipoCasoInicial}{$numeroSecuencial}";
    }
}
