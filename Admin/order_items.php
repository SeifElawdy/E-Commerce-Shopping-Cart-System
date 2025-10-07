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

if ($show == "all" && isset($order_id)):

    $stmt = $connect->prepare("
        SELECT 
            order_items.id AS item_id,
            products.name AS product_name,
            products.image AS product_image,
            order_items.quantity,
            order_items.price,
            (order_items.quantity * order_items.price) AS total
        FROM order_items
        JOIN products ON order_items.product_id = products.id
        WHERE order_items.order_id = ?
    ");
    $stmt->execute([$order_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <div class="container my-5">
        <?php if (isset($_SESSION['del_message']) && !empty($_SESSION['del_message'])): ?>
            <div class="alert alert-info text-center mt-3">
                <strong><?= $_SESSION['del_message']; ?></strong>
            </div>
            <?php unset($_SESSION['del_message']); ?>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Item ID</th>
                        <th>Product Name</th>
                        <th>Image</th>
                        <th>Quantity</th>
                        <th>Price (per unit)</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $grand_total = 0;
                    foreach ($items as $item):
                        $grand_total += $item['total'];
                    ?>
                        <tr>
                            <td><?= $item['item_id'] ?></td>
                            <td><?= $item['product_name'] ?></td>
                            <td>
                                <img src="../<?= $item['product_image'] ?>"
                                    class="img-thumbnail"
                                    style="width:90px; height:100px; object-fit:cover;">
                            </td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= $item['price'] ?></td>
                            <td><?= $item['total'] ?></td>
                            <td>
                                <a href="?show=delete&item_id=<?= $item['item_id'] ?>&order_id=<?= $order_id ?>"
                                    class="btn btn-sm btn-danger">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="d-flex justify-content-end mb-3">
                <div class="card shadow-sm border-0" style="min-width: 180px; background: linear-gradient(125deg, #a72879, #064497);">
                    <div class="card-body text-white text-center p-2">
                        <h6 class="mb-1">Order Total</h6>
                        <h5 class="fw-bold mb-0"><?= $grand_total ?></h5>
                    </div>
                </div>
            </div>


            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="orders_actions.php?show=all" class="btn btn-sm btn-primary">Back to Orders</a>
            </div>
        </div>
    </div>

<?php
endif;

if ($show == "delete" && isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];

    $stmt = $connect->prepare("DELETE FROM `order_items` WHERE `id`=?");
    $stmt->execute([$item_id]);

    $_SESSION['del_message'] = "Item Deleted Successfully";

    header("Location:order_items.php?show=all&order_id=$order_id");
}
?>