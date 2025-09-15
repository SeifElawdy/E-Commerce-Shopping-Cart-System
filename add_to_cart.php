<?php 
session_start();
require "../shop-homepage/connection/db_con.php";


if(!isset($_SESSION['user_id'])) {
    header("Location: login/login.html");
    exit();
}

if(isset($_SESSION['user_id'])){
    $user_id=$_SESSION['user_id'];

    if(isset($_GET['id'])){
        $product_id=$_GET['id'];
        
        try{
        $stmt=$connect->prepare("SELECT * FROM `cart` WHERE `user_id` = ? AND `product_id` = ?");
        $stmt->execute([$user_id,$product_id]);
        $existing= $stmt->fetch(PDO::FETCH_ASSOC);

        if($existing){
            $stmt=$connect->prepare("UPDATE `cart` SET `quantity` = `quantity`+1 WHERE `user_id`=? AND `product_id` = ?");
            $stmt->execute([$user_id,$product_id]);
        }else{
            $stmt=$connect->prepare("INSERT INTO `cart` (`user_id`,`product_id`,`quantity`) VALUES (?,?,1)");
            $stmt->execute([$user_id,$product_id]);
        }
    
    header("Location: index.php");
    }catch(PDOException $e){
        echo "An error occurred while adding the product to the cart:". htmlspecialchars($e->getMessage());
        error_log("Error in add_to_cart.php: " . $e->getMessage(), 3, "errors.log");
    }
}else{
        echo "Select product";
    }
}else{
    header("Location:/login/login.html");
    exit();
}




?>