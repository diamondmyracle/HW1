<?php
define('DBSERVER', 'localhost'); // Database server
define('DBUSERNAME', 'root'); // Database username
define('DBPASSWORD', ''); // Database password
define('DBNAME', 'app-db'); // Database name
 
/* connect to MySQL database */
$db = mysqli_connect(DBSERVER, DBUSERNAME, DBPASSWORD, DBNAME);
 
// Check db connection
if (!$db) {
    die(json_encode([
        "success" => false,
        "message" => "Database connection failed: " . mysqli_connect_error()
    ]));
}
?>
