<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RunBackgroundJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    /**
     * Number of retry attempts.
     *
     * @var int
     */
    public $tries;

    /**
     * Number of seconds to wait before retrying.
     *
     * @var int|array
     */
    public $backoff;

    /**
     * Create a new job instance.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->tries = config('queue.jobs.retry_attempts', 3);
        $this->backoff = config('queue.jobs.retry_backoff', 10);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Log::channel('background_jobs')->info('RunBackgroundJob started at ' . now());

            if (rand(0, 1)) {
                throw new \Exception('Simulated error in RunBackgroundJob for testing.');
            }

            Log::channel('background_jobs')->info('RunBackgroundJob completed successfully at ' . now());
        } catch (\Throwable $e) {

            Log::channel('background_jobs_errors')->error('RunBackgroundJob encountered an error: ' . $e->getMessage());
            Log::channel('background_jobs_errors')->error('Stack trace: ' . $e->getTraceAsString());

            throw $e;
        }
    }

    /**
     * Handle job failure.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        Log::channel('background_jobs_errors')->error('RunBackgroundJob failed permanently: ' . $exception->getMessage());
        Log::channel('background_jobs_errors')->error('Stack trace: ' . $exception->getTraceAsString());
    }
}
