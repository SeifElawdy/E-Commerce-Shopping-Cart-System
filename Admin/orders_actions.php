<?php

session_start();
require "../connection/db_con.php";
include("nav.php");


if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}


$show = "all";
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['show']) && isset($_GET['order_id'])) {
        $show = $_GET['show'];
        $order_id = $_GET['order_id'];
    }
}

if ($show == "all"):

    $stmt = $connect->prepare("
        SELECT 
            orders.id AS order_id,
            users.name AS user_name,
            orders.total_price,
            orders.created_at,
            COUNT(order_items.id) AS items_count
        FROM orders
        JOIN users ON orders.user_id = users.id
        LEFT JOIN order_items ON orders.id = order_items.order_id
        GROUP BY orders.id, users.name, orders.total_price, orders.created_at
        ORDER BY orders.created_at DESC
    ");
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <div class="container my-5">

<?php if(isset($_SESSION['del_message']) && !empty($_SESSION['del_message'])): ?>
    <div class="alert alert-info text-center mt-3"> 
        <strong><?= $_SESSION['del_message']; ?></strong>
    </div>
    <?php unset($_SESSION['del_message']); ?>
<?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>User Name</th>
                        <th>Items Count</th>
                        <th>Total Price</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= $order['order_id'] ?></td>
                            <td><?= $order['user_name'] ?></td>
                            <td><?= $order['items_count'] ?></td>
                            <td><?= $order['total_price'] ?></td>
                            <td><?= $order['created_at'] ?></td>
                            <td>
                                <a href="order_items.php?show=all&order_id=<?= $order['order_id'] ?>" class="btn btn-sm btn-info">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="?show=delete&order_id=<?= $order['order_id'] ?>" class="btn btn-sm btn-danger">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="dashboard.php?show=all" class="btn btn-sm btn-primary">Back</a>
            </div>
        </div>
    </div>

<?php
endif;

if ($show == "delete") {

    $stmt = $connect->prepare("DELETE FROM `order_items` WHERE `order_id`=?");
    $stmt->execute([$order_id]);


    $stmt = $connect->prepare("DELETE FROM `orders` WHERE `id`=?");
    $stmt->execute([$order_id]);
    $_SESSION['del_message'] = "Deleted Successfully";
    header("Location:orders_actions.php?show=all");

}



?>