<?php
//define('DB_SERVER', 'localhost');
//define('DB_USERNAME', 'root');
//define('DB_PASSWORD', '');
//define('DB_NAME', 'isp_ys96');
 
/* Attempt to connect to MySQL database */
//$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
//$mysqli = new mysqli('localhost', 'root', '', 'database_ys96');
$link = mysqli_connect('localhost', 'root', '', 'database_ys96');
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
?>