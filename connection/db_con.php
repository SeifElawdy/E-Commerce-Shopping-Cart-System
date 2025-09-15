<?php 

$host="localhost";
$dbname="shop";
$user="root";
$pass="";
$dsn="mysql:host=$host;dbname=$dbname";

try{
    $connect= new PDO($dsn,$user,$pass);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e) {
    die("DB Error: " . $e->getMessage());
}


?>