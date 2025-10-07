<?php 

$host="sql105.infinityfree.com";
$dbname="if0_39943001_shop";
$user="if0_39943001";
$pass="oWFikf4wC5eA";
$dsn="mysql:host=$host;dbname=$dbname";

try{
    $connect= new PDO($dsn,$user,$pass);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e) {
    die("DB Error: " . $e->getMessage());
}


?>