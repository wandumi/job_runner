<?php

use Illuminate\Support\Facades\Artisan;

if (!function_exists('runBackgroundJob')) {
    /**
     * Run a Laravel job in the background.
     *
     * @param string $jobClass
     * @param array $parameters
     * @return void
     */
    function runBackgroundJob(string $jobClass, array $parameters = [])
    {

        $jobInstance = new $jobClass(...$parameters);


        dispatch($jobInstance);

        if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {

            pclose(popen("start /B php artisan queue:work --once", "r"));
        } else {

            exec("php artisan queue:work --once > /dev/null 2>&1 &");
        }
    }
}
