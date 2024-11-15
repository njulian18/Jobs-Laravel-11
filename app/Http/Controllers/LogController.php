<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    
    public function getLogs()
    {
        $logPaths = [
            'background_jobs' => storage_path('logs/background_jobs.log'),
            'background_jobs_errors' => storage_path('logs/background_jobs_errors.log')
        ];

        $logEntries = [];

        foreach ($logPaths as $source => $filePath) {
            if (File::exists($filePath)) {
                $logs = File::get($filePath);

                foreach (explode("\n", $logs) as $line) {
                    if (!empty($line)) {
                        $logEntries[] = [
                            'date' => substr($line, 0, 19), 
                            'message' => $line,
                            'level' => $this->getLogLevel($line),
                            'source' => $source, 
                        ];
                    }
                }
            }
        }

        return response()->json($logEntries);
    }

    private function getLogLevel($line)
    {
        if (str_contains($line, 'ERROR')) {
            return 'Error';
        } elseif (str_contains($line, 'INFO')) {
            return 'Success';
        } elseif (str_contains($line, 'WARNING')) {
            return 'warning';
        }
        return 'debug';
    }

}
