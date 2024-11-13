<?php

namespace JobRunner;

class JobLogger
{
    /**
     * writing messages to a log file.
     */
    public static function log($message, $file = 'job_runner.log')
    {
        $logFile = __DIR__ . "../storage/{$file}";
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
    }

    /**
     * Log when a job starts running.
     */
    public static function running($className, $methodName)
    {
        self::log("Status: Running - $className::$methodName");
    }

    /**
     * Log when a job completes successfully.
     */
    public static function completed($className, $methodName)
    {
        self::log("Status: Completed - $className::$methodName");
    }

    /**
     * Log when a job fails.
     */
    public static function failed($className, $methodName, $error)
    {
        self::log("Status: Failed - $className::$methodName - Error: $error", 'background_jobs_errors.log');
    }

    /**
     * Log an exception
     */
    public static function logException(\Exception $e)
    {
        $errorMessage = $e->getMessage();
        $stackTrace = $e->getTraceAsString();
        self::log("Exception: $errorMessage\nStack Trace:\n$stackTrace", 'background_jobs_errors.log');
    }
}
