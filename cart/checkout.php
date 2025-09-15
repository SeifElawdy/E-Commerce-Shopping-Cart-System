
<?php 
session_start();
require "../connection/db_con.php";

if(!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id=$_SESSION['order_id'];
try {
    $stmt = $connect->prepare("SELECT cart.product_id, cart.quantity, products.price 
                            FROM cart  
                            JOIN products ON cart.product_id = products.id 
                            WHERE cart.user_id = ?");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(!$cart_items) {
        echo "Your cart is empty!";
        exit();
    } 


    $total_amount = 0;
    foreach($cart_items as $item) {
        $total_amount += $item['quantity'] * $item['price'];
    }


    $stmt = $connect->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) 
                                VALUES (?, ?, ?, ?)");
    foreach($cart_items as $item) {
        $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
    }

    $stmt = $connect->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);

    $_SESSION['product_number'] = 0;

}catch(PDOException $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
    error_log("Checkout Error: " . $e->getMessage(), 3, "errors.log");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        
        .center-alert {
            width: 60%;          
            padding: 40px;         
            margin: 100px auto;   
            border-radius: 10px;   
            text-align: center;    
        }
    </style>
</head>
<body>
    <div class="center-alert bg-success text-white">
    <div>
        <h1>Payment Successful!</h1>
        <p>Thank you for your purchase.</p>
    </div>
</div>
</body>
</html>




<?php

header("Refresh: 3; URL=../index.php");
exit();

?>