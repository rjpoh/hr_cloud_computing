<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'assignmentdb.cstcxmslvisd.us-east-1.rds.amazonaws.com');
define('DB_USERNAME', 'main');
define('DB_PASSWORD', 'assingmentpw');
define('DB_NAME', 'hr_system_cloudcomputing');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
