## About Me

I am experienced in developing web applications from the ground up utilizing a number of technologies. My background involves backend programming, API creation, and collaboration with a variety of technologies. I am known for my professionalism and commitment to the organizations with which I have worked.

## TASK 1: Job Runner in Job Runner Folder

The PHP code implements a Background Job Runner that enables executing predefined class methods with specified input parameters via the command line. It creates an instance of the target class, executes the method, and logs the job data (class, method, parameters) along with the result status (success or failure). This system handles background tasks effectively and logs detailed information for each job's execution. The job runner is compatible with both Unix and Windows systems and uses the **vlucas/phpdotenv** package for environment configuration, including setting a retry limit (`MAX_RETRIES`). To execute jobs, navigate to the JobRunner folder and use the following command format:

```bash
php execute.php JobService execute "Hello" "World"
```

The job runner enhances security by only allowing pre-approved classes and sanitizing inputs to prevent unauthorized or malicious execution. It features strong error handling, logs errors in a separate file (`background_jobs_errors.log`), and tracks job statuses (running, completed, or failed) with timestamps for debugging. Example test commands include:

```bash
php execute.php JobServices execute "Test"       # Fails due to unauthorized class
php execute.php JobService execute "Test"        # Fails due to missing parameters
php execute.php JobService execute "Test" "Test" # Success
```

These tests demonstrate how the system handles different input scenarios, ensuring only valid, authorized commands are executed.

## TASK 2: Global function for Job Execution

I developed the `runBackgroundJob` global function in Laravel, which enables us to execute operations in the background on both Windows and Unix platforms. A button on the homepage initiates the job, which executes without requiring the user to wait.

The user is taken back to the homepage after clicking, and Laravel's queue system keeps the job running in the background.

## TASK 3: Feature Requirements

I implemented a RunBackgroundJob class in Laravel with enhanced error handling, logging, and a configurable retry mechanism. It logs job status and errors separately, using background_jobs.log and background_jobs_errors.log.

The retry mechanism allows the job to be retried up to 5 times

Add these in the .env when when you clone the files, they are used on the running of the RunBackgroundJob.

```bash
QUEUE_RETRY_ATTEMPTS=5
QUEUE_RETRY_BACKOFF=15
```

## TASK 4: Security Requirements

I added security checks to the runBackgroundJob function by verifying and cleaning the job class name to stop unwanted code execution. Only authorized job classes are allowed to execute in the background thanks to a whitelist.

Background_jobs_errors.log records any attempts to use an invalid or illegal job class for monitoring purposes. By using this method, malicious input is prevented and background execution is limited to trustworthy jobs.
