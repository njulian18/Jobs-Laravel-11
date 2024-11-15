<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log; // Importar la facade Log

class ExampleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        try {
            // Procesar el Job
            Log::info('Procesando el Job', ['data' => $this->data]);

            // Simular algún trabajo
            sleep(5);

            // Tirar un mensaje en el log al finalizar
            Log::info('Job procesado exitosamente', ['data' => $this->data]);
        } catch (\Exception $e) {
            // Tirar un mensaje en el log si el Job falla
            Log::error('Job falló', ['data' => $this->data, 'error' => $e->getMessage()]);

            // Puedes lanzar la excepción nuevamente si deseas que el job sea reintentado
            throw $e;
        }
    }

    public function failed(\Exception $exception)
    {
        // Tirar un mensaje en el log si el Job falla
        Log::error('Job falló', ['data' => $this->data, 'error' => $exception->getMessage()]);
    }
}


