<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'us-cdbr-east-04.cleardb.com:3306');
define('DB_USERNAME', 'bf06c5abed4071');
define('DB_PASSWORD', 'd17a78eb');
define('DB_NAME', 'heroku_85a19c241dcb163');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>