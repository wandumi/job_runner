<?php

use JobRunner\JobLogger;

require_once __DIR__ . '/JobLogger.php';
require_once __DIR__ . '/JobService.php';
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$className = $argv[1] ?? null;
$methodName = $argv[2] ?? null;
$params = array_slice($argv, 3);
$maxRetries = (int) getenv('MAX_RETRIES') ?: 3;
$attempt = 0;

if ($className && $methodName) {
    try {
        // Validate the class and method
        if (!class_exists("JobRunner\\$className")) {
            throw new \Exception("Class $className not found.");
        }

        $service = new ("JobRunner\\$className");

        if (!method_exists($service, $methodName)) {
            throw new \Exception("Method $methodName not found in class $className.");
        }

        // Check the number of required arguments
        $reflection = new \ReflectionMethod($service, $methodName);
        $requiredParamsCount = $reflection->getNumberOfRequiredParameters();

        if (count($params) < $requiredParamsCount) {
            throw new \Exception("Too few arguments provided for $className::$methodName. Expected $requiredParamsCount, got " . count($params) . ".");
        }

        while ($attempt < $maxRetries) {
            try {
                $attempt++;
                JobLogger::running($className, $methodName);

                // Execute the method
                call_user_func_array([$service, $methodName], $params);
                JobLogger::completed($className, $methodName);

                break; // Exit loop on success
            } catch (\Exception $e) {
                // Log the error and mark the job as failed
                JobLogger::failed($className, $methodName, $e->getMessage());
                JobLogger::logException($e);

                if ($attempt >= $maxRetries) {
                    JobLogger::log("Max retry attempts reached for $className::$methodName", 'background_jobs_errors.log');
                    break;
                }
                sleep(2); // Optional delay before retrying
            }
        }
    } catch (\Exception $e) {
        // Log any uncaught exceptions
        JobLogger::logException($e);
        JobLogger::log("Uncaught exception in execute.php: " . $e->getMessage(), 'background_jobs_errors.log');
    }
} else {
    echo "Usage: php execute.php <ClassName> <MethodName> [params...]\n";
}