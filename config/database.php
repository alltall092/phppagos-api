<?php
// Database configuration

class db {

public   static function conexion(){
define('DB_HOST', 'boeu8hvoi5q1q4wyoase-mysql.services.clever-cloud.com');
define('DB_NAME', 'boeu8hvoi5q1q4wyoase');
define('DB_USER', 'ukr8e6mcmkilza1u');
define('DB_PASS', 'oQTd5VNutRFvSqQbml3A');

 $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);

}
return $conn;
}
}