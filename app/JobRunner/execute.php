<?php

require_once __DIR__ . '/JobLogger.php';
require_once __DIR__ . '/JobService.php';
require_once __DIR__ . '/vendor/autoload.php';

use JobRunner\JobLogger;
use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$className = $argv[1] ?? null;
$methodName = $argv[2] ?? null;
$params = array_slice($argv, 3);
$maxRetries = (int) getenv('MAX_RETRIES') ?: 3;
$attempt = 0;


$allowedClasses = [
    'JobService'
];


function sanitizeInput($input)
{
    return preg_replace('/[^a-zA-Z0-9_\\\\]/', '', $input);
}

$className = sanitizeInput($className);
$methodName = sanitizeInput($methodName);


if (!in_array($className, $allowedClasses, true)) {
    $errorMessage = "Error: Unauthorized class $className.";
    JobLogger::log($errorMessage, 'background_jobs_errors.log');
    exit($errorMessage . "\n");
}


if (!$className || !$methodName) {
    $errorMessage = "Error: Missing class name or method name.\nUsage: php execute.php <ClassName> <MethodName> [params...]";
    JobLogger::log($errorMessage, 'background_jobs_errors.log');
    exit($errorMessage . "\n");
}


try {
    if (!class_exists("JobRunner\\$className")) {
        throw new \Exception("Class $className not found.");
    }

    $service = new ("JobRunner\\$className");

    if (!method_exists($service, $methodName)) {
        throw new \Exception("Method $methodName not found in class $className.");
    }

    $reflection = new \ReflectionMethod($service, $methodName);
    $requiredParamsCount = $reflection->getNumberOfRequiredParameters();


    if (count($params) < $requiredParamsCount) {
        $errorMessage = "Error: Too few arguments provided for $className::$methodName. Expected $requiredParamsCount, got " . count($params) . ".";
        JobLogger::log($errorMessage, 'background_jobs_errors.log');
        exit($errorMessage . "\n");
    }

    while ($attempt < $maxRetries) {
        try {
            $attempt++;
            JobLogger::log("Running: $className::$methodName with parameters: " . implode(", ", $params));

            call_user_func_array([$service, $methodName], $params);
            JobLogger::log("Completed: $className::$methodName");

            break; 
        } catch (\Exception $e) {
            $errorMessage = "Failed: $className::$methodName - Error: " . $e->getMessage();
            JobLogger::log($errorMessage, 'background_jobs_errors.log');
            echo $errorMessage . "\n";

            if ($attempt >= $maxRetries) {
                $maxRetryMessage = "Max retry attempts reached for $className::$methodName";
                JobLogger::log($maxRetryMessage, 'background_jobs_errors.log');
                echo $maxRetryMessage . "\n";
                break;
            }
            sleep(2);
        }
    }
} catch (\Exception $e) {
    $errorMessage = "Uncaught exception in execute.php: " . $e->getMessage();
    JobLogger::log($errorMessage, 'background_jobs_errors.log');
    exit($errorMessage . "\n");
}
