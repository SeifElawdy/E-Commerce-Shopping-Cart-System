<?php
session_start();
require "../connection/db_con.php";


if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['action'])) {
        $user_id = $_GET['user_id'];
        $action = $_GET['action'];

        if ($action == "view") :
            $stmt = $connect->prepare("SELECT * FROM `orders` WHERE `user_id` = ?");
            $stmt->execute([$user_id]);
            $o_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $_SESSION['o_data'] = $o_data;
            $_SESSION['show_o'] = "view";

           
            header("Location:dashboard.php");
        endif;

        if ($action == "delete"):
            $stmt = $connect->prepare("SELECT role FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $role = $stmt->fetchColumn();

            if ($role === 'admin') {
                $_SESSION['delete_message'] = "You cannot delete the ADMIN user!";
            } else {
                $stmt = $connect->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $_SESSION['delete_message'] = "Deleted Successfully";
            }

            header("Location:dashboard.php?show=users");
            exit();
        endif;
    }
    //////////////////////////////////////////////////////////////////////////////////////////
    if (isset($_GET['show']) && isset($_GET['order_id'])) {
        if ($_GET['show'] == "details") {
            $order_id = $_GET['order_id'];
            $stmt = $connect->prepare(" SELECT order_items.*, products.name FROM order_items 
            JOIN products ON order_items.product_id = products.id 
            WHERE order_items.order_id = ?");
            $stmt->execute([$order_id]);
            $item_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $_SESSION['item_data'] = $item_data;
            $_SESSION['show_i'] = "details";
            header("Location:dashboard.php");
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['pass']) && isset($_POST['confirm_pass'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $pass = $_POST['pass'];
        $confirm_pass = $_POST['confirm_pass'];
        if (trim($pass) != trim($confirm_pass)) {
            $_SESSION['pass_message'] = "The password does not match.";
            header("Location:dashboard.php?show=add");
        } else {
            $stmt = $connect->prepare("SELECT * FROM `users` WHERE `email`= ? ");
            $stmt->execute([$email]);
            $rows = $stmt->rowCount();
            if ($rows == 0) {
                $hashedPass = password_hash($pass, PASSWORD_DEFAULT);
                $stmt = $connect->prepare("INSERT INTO `users` (`name`,`email`,`password`) VALUES(?,?,?)");
                $stmt->execute([$name, $email, $hashedPass]);
                $_SESSION['pass_message'] = "User Added Successfully";
                header("Location:dashboard.php?show=add");
            } else {
                $_SESSION['pass_message'] = "email already exist !!";
                header("Location:dashboard.php?show=add");
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['action']) && isset($_POST['user_id'])):
        $action = $_POST['action'];
        $user_id = $_POST['user_id'];
        if ($action == "edit"):
            if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['pass'])) :
                $name = $_POST['name'];
                $email = $_POST['email'];
                $pass = $_POST['pass'];
                $stmt = $connect->prepare("SELECT * FROM `users` WHERE `email`= ? ");
                $stmt->execute([$email]);
                $rows = $stmt->rowCount();
                if ($rows == 0) {
                    $hashedPass = password_hash($pass, PASSWORD_DEFAULT);
                    $stmt = $connect->prepare("UPDATE `users` SET `name`=? , `email`=? , `password`=? WHERE `id`= ?  ");
                    $stmt->execute([$name, $email, $hashedPass, $user_id]);
                    $_SESSION['edit_message'] = "Updated successfully";
                } else {
                    $_SESSION['edit_message'] = "email already exist !!";
                }
                header("Location:dashboard.php?show=edit");
            endif;
        endif;
    endif;
}
