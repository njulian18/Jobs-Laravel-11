<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ExampleJob; // Asegúrate de usar tu Job creado
use Illuminate\Support\Facades\Log; // Importar la facade Log

class JobTestController extends Controller
{
    public function dispatchJob()
    {
        $data = ['message' => 'Esto es una prueba de un Job en Laravel'];

        // Despachar el Job
        ExampleJob::dispatch($data);

        // Tirar un mensaje en el log
        Log::info('Job despachado exitosamente', ['data' => $data]);

        return response()->json(['status' => 'Job despachado exitosamente']);
    }
}
