<?php 
session_start();
require "../connection/db_con.php";

    if($_SERVER['REQUEST_METHOD']=='POST'){
        if(isset($_POST['email']) && isset($_POST['password'])){
            $email=$_POST['email'];
            $pass=$_POST['password'];

            $stmt=$connect->prepare("SELECT * FROM `users` WHERE `users`.`email` = ? ");
            $stmt->execute([$email]);

            if($stmt->rowCount() === 1){

                $user=$stmt->fetch(PDO::FETCH_ASSOC);

                if(password_verify($pass, $user['password'])){
                    $_SESSION['user_id']=$user['id'];
                    $_SESSION['user_name']=$user['name'];
                    ///////
                    header("Location: ../index.php");
                    exit();
                }else{
                    echo "<h4>Wrong Password</h4>";
                }
            }else{
                echo "<h4>Wrong email</h4>";
            }
        }else{
            echo "<h4>Enter email or password</h4>";
        }
    }

?>