<?php

namespace JobRunner;

class JobLogger
{
    public static function log($message)
    {
        $logFile = __DIR__ . '../storage/job_runner.log';
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
    }

    public static function success($className, $methodName)
    {
        self::log("Success: $className::$methodName");
    }

    public static function failure($className, $methodName, $error)
    {
        self::log("Failure: $className::$methodName - Error: $error");
    }
}
