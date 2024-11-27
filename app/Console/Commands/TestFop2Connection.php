<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestFop2Connection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fop2:test-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the connection to the FOP2 API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Configura los detalles de tu API FOP2
        $apiUrl = config('fop2.api_url'); // Asegúrate de configurar esto en tu archivo de configuración
        $apiUser = config('fop2.api_user'); // Usuario para autenticación
        $apiToken = config('fop2.api_token'); // Token de API

        // Intenta hacer la conexión
        try {
            $response = Http::get($apiUrl, [
                'user' => $apiUser,
                'token' => $apiToken,
            ]);

            if ($response->ok()) {
                $this->info('Conexión exitosa: ' . $response->body());
            } else {
                $this->error('Error en la conexión: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->error('Excepción al conectar: ' . $e->getMessage());
        }

        return 0;
    }
}
