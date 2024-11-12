<?php

require_once __DIR__ . '/JobLogger.php';
require_once __DIR__ . '/JobService.php';

use JobRunner\JobLogger;
use JobRunner\JobService;

$className = $argv[1] ?? null;
$methodName = $argv[2] ?? null;
$params = array_slice($argv, 3);

if ($className && $methodName) {
    try {
        $service = new JobService();
        JobLogger::log("Starting $className::$methodName with parameters: " . implode(", ", $params));
        
        // Execute the method and log success
        call_user_func_array([$service, $methodName], $params);
        JobLogger::success($className, $methodName);
    } catch (\Exception $e) {
        // Log failure if an exception occurs
        JobLogger::failure($className, $methodName, $e->getMessage());
    }
} else {
    echo "Usage: php execute.php <ClassName> <MethodName> [params...]\n";
}
