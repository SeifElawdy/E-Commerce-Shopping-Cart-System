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

if ($show == "all"):

    $stmt = $connect->prepare("
        SELECT 
            users.id AS user_id,
            users.name AS user_name,
            SUM(products.price * cart.quantity) AS cart_total,
            (
                SELECT SUM(products.price * cart.quantity)
                FROM cart
                JOIN products ON cart.product_id = products.id
            ) AS total_cart_value
        FROM cart
        JOIN users ON cart.user_id = users.id
        JOIN products ON cart.product_id = products.id
        GROUP BY users.id, users.name
    ");
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <div class="container my-5">
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>User ID</th>
                        <th>User Name</th>
                        <th>Cart Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <td><?= $row['user_id'] ?></td>
                            <td><?= $row['user_name'] ?></td>
                            <td><?= $row['cart_total'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php
            $total_cart_value = 0;
            if (!empty($data)) {
                $total_cart_value = $data[0]['total_cart_value'] ?? 0;
            }
            ?>

            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body text-center">
                    <h5 class="card-title text-uppercase text-muted mb-2"> Total Value of All Carts</h5>
                    <h3 class="card-text text-success fw-bold">
                        <?= number_format($total_cart_value, 2) ?> EGP
                    </h3>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="dashboard.php?show=all" class="btn btn-sm btn-primary">Back</a>
            </div>
        </div>
    </div>

<?php
endif;
?>