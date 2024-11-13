<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('runBackgroundJob')) {
    /**
     * Run a Laravel job in the background with error handling and logging.
     *
     * @param string $jobClass The job class to execute.
     * @param array $parameters The parameters to pass to the job.
     * @return void
     */
    function runBackgroundJob(string $jobClass, array $parameters = [])
    {
        try {
         
            Log::channel('background_jobs')->info("Job {$jobClass} started at " . now());


            $jobInstance = new $jobClass(...$parameters);
            dispatch($jobInstance);


            Log::channel('background_jobs')->info("Job {$jobClass} dispatched at " . now());


            if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
                pclose(popen("start /B php artisan queue:work --once", "r"));
            } else {
                exec("php artisan queue:work --once > /dev/null 2>&1 &");
            }
        } catch (\Exception $e) {

            Log::channel('background_jobs_errors')->error("Error in job {$jobClass}: " . $e->getMessage());
        }
    }
}
