<?php

    namespace App\JobsRunner;

    use Illuminate\Support\Facades\Log;
    use App\Jobs\MyJobClass;
    use Exception;

    class BackgroundJobRunner
    {
        public function runJob($className, $methodName, array $parameters = [])
        {
            try {
                if (!in_array($className, $this->allowedClasses())) {
                    throw new Exception("Class not allowed for execution.");
                }
                $instance = new $className();
                if (!method_exists($instance, $methodName)) {
                    throw new Exception("Method $methodName does not exist in class $className.");
                }
                call_user_func_array([$instance, $methodName], $parameters);
                Log::channel('background_jobs')->info("Job executed successfully", [
                    'class' => $className,
                    'method' => $methodName,
                    'status' => 'success',
                    'timestamp' => now(),
                ]);
            } catch (Exception $e) {
                Log::channel('background_jobs_errors')->error("Failed to execute job", [
                    'class' => $className,
                    'method' => $methodName,
                    'error' => $e->getMessage(),
                    'timestamp' => now(),
                ]); 
                throw $e;
            }
        }

        private function allowedClasses()
        {
            return [
                MyJobClass::class, 
            ];
        }
    }
