<?php
session_start();
require "../connection/db_con.php";

if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['name'])){

        $name=$_POST['name'];
        $email=$_POST['email'];
        $pass=$_POST['password'];
        $hashedPass=password_hash($pass,PASSWORD_DEFAULT);

    $stmt=$connect->prepare("INSERT INTO `users` (`name`,`email`,`password`) VALUES (?,?,?)");
    $stmt->execute([$name,$email,$hashedPass]);

    
    $id_iserted=$connect->lastInsertId();

    $_SESSION['user_id']=$id_iserted;
    $_SESSION['user_name']=$name;
    header("Location:../index.php");
    exit();
    }else{
        echo "<h4>Enter all the fields</h4>";
    }
}


?>