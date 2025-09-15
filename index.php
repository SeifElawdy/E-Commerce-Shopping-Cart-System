<?php
session_start();
require "connection/db_con.php";

if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // حساب عدد المنتجات في الكارت من قاعدة البيانات
    $stmt = $connect->prepare("SELECT SUM(quantity) AS total_products FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $_SESSION['product_number'] = $result['total_products'] ?? 0;
} else {
    $_SESSION['product_number'] = 0;
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Shop Homepage - Start Bootstrap Template</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
        <style>
            .product-img-container {
        height: 200px; 
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        }
        .product-img-container img {
        max-height: 100%;
        width: auto;
        object-fit: cover;
        }
        </style>
    </head>
    <body>
        <!-- Navigation-->

<nav class="navbar navbar-expand-lg navbar-light bg-light px-3">
    <div class="container-fluid px-0">
        <!-- Brand -->
    <span class="navbar-brand fs-3 fw-bold text-danger">Seif's Website</span>


        <!-- Toggler for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar content -->
        <div class="d-flex me-auto mb-2 mb-lg-0 ms-lg-4">
    <span class="navbar-text me-3 fs-5 fw-bold">Welcome!</span>
    <span class="navbar-text me-3 fs-5 fw-bold">Best Deals Here</span>
    <span class="navbar-text fs-5 fw-bold">Free Shipping</span>
</div>


            <!-- Left nav  -->
            <!-- Right buttons: Cart + Login/Logout -->
            <div class="d-flex ms-auto pe-2">
                <!-- Cart Button -->
                <form class="me-1" action="cart/cart.php" method="post">
                    <button class="btn btn-outline-dark" type="submit">
                        <i class="bi-cart-fill me-1"></i>
                        Cart
                        <?php 
                        if(!isset($_SESSION['product_number'])) {
                            $product_number=0;
                        } else {
                            $product_number=$_SESSION['product_number'];
                        }
                        ?>
                        <span class="badge bg-dark text-white ms-1 rounded-pill"><?=$product_number?></span>
                    </button>
                </form>

                <!-- Login / Logout Button -->
                <?php
                if(isset($_SESSION['user_id'])) {
                    echo '<a href="login/logout.php" class="btn btn-danger">Logout</a>';
                } else {
                    echo '<a href="login/login.html" class="btn btn-success">Login</a>';
                }
                ?>
            </div>
        </div>
    </div>
</nav>

        <!-- Header-->
        <header class="bg-dark py-5">
            <div class="container px-4 px-lg-5 my-5">
                <div class="text-center text-white">
                    <h1 class="display-4 fw-bolder">Your Style, Your Way</h1>
                    <p class="lead fw-normal text-white-50 mb-0">Find what makes you unique and stand out!</p>
                </div>
            </div>
        </header>
        <!-- Section-->
        <section class="py-5">
            <div class="container px-4 px-lg-5 mt-5">
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">

                        <!-- ----------------------------------------------------------------------- -->
                        <?php                         
                        $stmt=$connect->prepare("SELECT * FROM `products`");
                        $stmt->execute();
                        while($row=$stmt->fetch(PDO::FETCH_ASSOC)):
                        ?>
                <div class="col mb-5">
    <div class="card h-100 d-flex flex-column">
        <!-- Product image -->
        <div class="product-img-container">
            <img class="card-img-top" src="<?=htmlspecialchars($row['image'])?>" alt="<?=htmlspecialchars($row['name'])?>" />
        </div>

        <!-- Product details -->
        <div class="card-body p-4 d-flex flex-column justify-content-between">
            <div class="text-center">
                <!-- Product name-->
                <h5 class="fw-bolder"><?=htmlspecialchars($row['name']) ?></h5>
                <!-- Product price-->
                $<?=htmlspecialchars($row['price'])?>
            </div>
        </div>

        <!-- Product actions -->
        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
            <div class="text-center">
                <a class="btn btn-outline-dark mt-auto" href="add_to_cart.php?id=<?=(int)$row['id']?>">Add to cart</a>
            </div>
        </div>
    </div>
</div>

                    <?php endwhile; ?>
                    
                </div>
            </div>
        </section>



        <!-- Footer-->
        <footer class="py-5 bg-dark">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Seif's Website</p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
