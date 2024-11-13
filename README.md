## About Me

I am experienced in developing web applications from the ground up utilizing a number of technologies. My background involves backend programming, API creation, and collaboration with a variety of technologies. I am known for my professionalism and commitment to the organizations with which I have worked.

## TASK 1: Job Runner in Job Runner Folder

The given PHP code implements a Background Job Runner mechanism. It enables you to invoke any defined class method with input parameters from the command line. It makes an instance of the specified class, executes the method, and records the results in a log file.

The log contains task data (class, method, and parameters) and indicates if the execution was successful or unsuccessful. This allows the system to perform background operations and keep track of each job's outcome.

To run the job, make sure you're in the JobRunner folder as follows.

c/xampp/htdocs/job_runner/App/JobRunner (main)

php execute.php JobService execute "Hello" "World"

## TASK 3: Feature Requirements

PHP's Background Job Runner was implemented in this task, which runs jobs in the background and logs their status (running, completed, or failed) together with timestamps. It has a configurable retry mechanism for unsuccessful jobs, strong error handling, and independent error reporting in background_jobs_errors.log.

The system is made to function flawlessly in both Unix-based and Windows-based systems, guaranteeing dependable and constant job execution.

Create .env file and add a variable called MAX_RETRIES and set the limit of the entries

I also used the following Package to enable .env in the PHP file

composer require vlucas/phpdotenv

## TASK 4: Security Requirements

In order to ensure that only pre-approved classes can run, this assignment created a secure PHP Background Job Runner that verifies and cleans user input. The script's configurable retry mechanism (MAX_RETRIES) is one of the environment variables it uses.

In addition to recording errors in a separate log file (background_jobs_errors.log), it has strong error handling and logs all job statuses (running, completed, and failed) with timestamps. Unauthorized class attempts are prohibited, and malicious code execution is prevented by input sanitization. The system keeps thorough logs for debugging and gives the user direct feedback through console output.

To test the feature you can run the following on the CMD mainly bash or git bash

php execute.php JobServices execute "Test" - wont work due to wrong allowed class

php execute.php JobService execute "Test" - wont work due to parameter/arguments

php execute.php JobService execute "Test" "Test" - success
