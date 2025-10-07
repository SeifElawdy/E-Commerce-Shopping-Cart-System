<?php 
session_start();
require "../connection/db_con.php";

if(isset($_SESSION['user_id']) && isset($_SESSION['product_id']) ){
    $user_id=$_SESSION['user_id'];
    $product_id=$_SESSION['product_id'];

    if($_POST['action'] == "add"){
        $product_id = $_POST['product_ID'];
        $stmt=$connect->prepare("UPDATE `cart` SET `quantity` = quantity + 1 WHERE user_id = ? AND `product_id`=?;");
        $stmt->execute([$user_id,$product_id]);
        header("Location: cart.php");
        exit();
    }

    if($_POST['action'] == "del" ){
        $product_id = $_POST['product_ID'];
        $stmt = $connect->prepare("UPDATE `cart` SET `quantity` = `quantity` - 1 WHERE `user_id` = ? AND `product_id` = ? AND `quantity` > 1;");
        $stmt->execute([$user_id, $product_id]);
            header("Location: cart.php");
            exit();
    }

    if(isset($_GET['delete'])){
        
        $product_id = $_GET['product_ID'];
        
        $stmt=$connect->prepare("DELETE FROM `cart` WHERE `cart`.`user_id` = ? AND `cart`.`product_id` = ?");
        $stmt->execute([$user_id,$product_id]);

        $stmt = $connect->prepare("SELECT SUM(quantity) AS total_products FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $_SESSION['product_number'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_products'] ?? 0;

        header("Location: cart.php");
        exit();
    }


    if(isset($_POST['checkout'])){
        $total=$_SESSION['total_price'];
        $stmt=$connect->prepare("INSERT INTO `orders` (`user_id`,`total_price`)  VALUES (?,?)");
        $stmt->execute([$user_id,$total]);
        $_SESSION['order_id'] = $connect->lastInsertId();
        header("Location: checkout.php");
        exit();
    }

    if(isset($_POST['back'])){
        header("Location: ../index.php");
        exit();
    }

}else{
    header("Location: ../login/login.html");
    exit();
}

?>