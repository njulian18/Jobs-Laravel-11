<?php

namespace App\Http\Controllers;

use App\Jobs\MyJobClass;
use Symfony\Component\Process\Process;
use App\JobsRunner\BackgroundJobRunner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;



class TestJobController extends Controller
{
    // éxito
    public function testSuccess()
    {
        $runner = new BackgroundJobRunner();
        try {
            $runner->runJob(MyJobClass::class, 'executeJobSuccessfully');
            return response()->json(['message' => 'Success test completed']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error during job execution',
                'error' => $e->getMessage(),
            ]);
        }
    }

    // fallo
    public function testFailure()
    {
        $runner = new BackgroundJobRunner();
        try {
            $exception = new \Exception("Simulated failure in job execution.");
             $runner->runJob(MyJobClass::class, 'executeJobWithFailure', [$exception]);
    
            return response()->json(['message' => 'Error test completed']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failure test completed',
                'error' => $e->getMessage(),
            ]);
        }
    }
    
    
    
    
    public function startWorker()
    {
        try {
            $process = new Process(['php', 'artisan', 'queue:work', '--daemon']);
            $process->start();
    
           
            sleep(2);
    
            if ($process->isRunning()) {
                echo "Worker started successfully!\n";  
                Log::info('Worker started!');
            } else {
                echo "Worker failed to start.\n";
                Log::error('Worker failed to start.');
            }
        } catch (\Exception $e) {
            echo 'Error starting worker: ' . $e->getMessage() . "\n";
            Log::error('Error starting worker: ' . $e->getMessage());
        }
    }
    
    

}
