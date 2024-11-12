## About Me

I am experienced in developing web applications from the ground up utilizing a number of technologies. My background involves backend programming, API creation, and collaboration with a variety of technologies. I am known for my professionalism and commitment to the organizations with which I have worked.

## TASK 1: Job Runner in Job Runner Folder

The given PHP code implements a Background Job Runner mechanism. It enables you to invoke any defined class method with input parameters from the command line. It makes an instance of the specified class, executes the method, and records the results in a log file.

The log contains task data (class, method, and parameters) and indicates if the execution was successful or unsuccessful. This allows the system to perform background operations and keep track of each job's outcome.

To run the job, make sure you're in the JobRunner folder as follows.

c/xampp/htdocs/job_runner/App/JobRunner (main)

php execute.php JobService execute "Hello" "World"
