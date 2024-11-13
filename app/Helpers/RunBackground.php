<?php

use App\Jobs\RunBackgroundJob;
use Illuminate\Support\Facades\Log;

if (!function_exists('runBackgroundJob')) {
    /**
     * Run a Laravel job in the background with security checks, error handling, and logging.
     *
     * @param string $jobClass The job class to execute.
     * @param array $parameters The parameters to pass to the job.
     * @return void
     */
    function runBackgroundJob(string $jobClass, array $parameters = [])
    {
        try {

            $approvedJobs = [
                RunBackgroundJob::class,
            ];

            $jobClass = trim($jobClass);

            if (!in_array($jobClass, $approvedJobs, true)) {
                Log::channel('background_jobs_errors')->error("Unauthorized job class: {$jobClass}");
                throw new \InvalidArgumentException("Unauthorized job class: {$jobClass}");
            }

            Log::channel('background_jobs')->info("Job {$jobClass} started at " . now());

            $jobInstance = new $jobClass(...$parameters);
            dispatch($jobInstance);

            if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
                pclose(popen("start /B php artisan queue:work --once", "r"));
            } else {
                exec("php artisan queue:work --once > /dev/null 2>&1 &");
            }

            Log::channel('background_jobs')->info("Job {$jobClass} dispatched at " . now());
        } catch (\Throwable $e) {
            Log::channel('background_jobs_errors')->error("Error in job {$jobClass}: " . $e->getMessage());
        }
    }
}
