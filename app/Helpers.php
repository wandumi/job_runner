<?php

if (!function_exists('runBackgroundJob')) {
    /**
     * Run a background job by executing a PHP class method.
     *
     * @param string $className The name of the class to execute.
     * @param string $methodName The method to call on the class.
     * @param array $params The parameters to pass to the method.
     */
    function runBackgroundJob(string $className, string $methodName, array $params = [])
    {
        $paramsString = implode(' ', array_map('escapeshellarg', $params));
        $execute = PHP_BINARY . ' ' . base_path('JobRunner/execute.php') . " $className $methodName $paramsString";

        if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
            pclose(popen("start /B $execute", 'r'));
        } else {
            exec("$execute > /dev/null 2>&1 &");
        }
    }
}
