<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MyJobClass implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    public function handle()
    {
        try {
            $this->executeJobSuccessfully();
        } catch (\Exception $e) {
            Log::channel('background_jobs_errors')->error('Exception caught in handle: ' . $e->getMessage());
            $this->executeJobWithFailure($e);
        }
    }

    public function executeJobSuccessfully()
    {
        Log::info('Job ejecutado exitosamente!');
    }

    public function executeJobWithFailure($e)
    {
        Log::channel('background_jobs_errors')->error('Fallo al ejecutar el job: ' . $e->getMessage());

        throw new \Exception("Simulated failure in job execution.");
    }
}
