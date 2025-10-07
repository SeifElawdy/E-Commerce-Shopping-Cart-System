<?php
session_start();
require "../connection/db_con.php";

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login/login.html");
  exit();
}

if ($_SESSION['role'] !== 'admin') {
  header("Location: ../index.php");
  exit();
}

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <?php
  include("nav.php");
  ?>

  <!-- --------------------------------------------------------------------------- -->
  <?php
  $show = "all";
  if (isset($_SESSION['o_data']) && isset($_SESSION['show_o']) && isset($_SESSION['user_name'])) {
    $o_data = $_SESSION['o_data'];
    $show = $_SESSION['show_o'];
    $user_name = $_SESSION['user_name'];
  }

  if (isset($_SESSION['show_i']) && isset($_SESSION['item_data'])) {
    $show = $_SESSION['show_i'];
    $items_data = $_SESSION['item_data'];
  }

  if (isset($_GET['show'])) {
    $show = $_GET['show'];
  }

  // <--     ------------------------------------------------------------------------------     -->
  if ($show == "all"):
  ?>
    <section class="container mb-5">
      <div class="row g-4 justify-content-center">
        <!-- Users -->
        <div class="col-md-4 col-lg-3">
          <div class="card card-pricing text-center h-100">
            <div class="card-header bg-primary text-white border-0">
              <h5 class="mb-0">Users</h5>
            </div>
            <div class="card-body">
              <i class="fa-solid fa-users fa-4x"></i>
              <ul class="list-unstyled mt-3 mb-4 small ">
                <?php
                $stmt = $connect->prepare("SELECT * FROM `users`");
                $stmt->execute();
                $users_num = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <li>
                  <h6><strong><?= count($users_num); ?> users</strong></h6>
                </li>
              </ul>
              <a href="?show=users" class="btn btn-primary w-100">Show</a>
            </div>
          </div>
        </div>

        <!-- Products -->
        <div class="col-md-4 col-lg-3">
          <div class="card card-pricing text-center h-100">
            <div class="card-header bg-primary text-white border-0">
              <h5 class="mb-0">products</h5>
            </div>
            <div class="card-body">
              <i class="fa-solid fa-cubes fa-4x"></i>
              <ul class="list-unstyled mt-3 mb-4 small ">
                <?php
                $stmt = $connect->prepare("SELECT * FROM `products`");
                $stmt->execute();
                $products_num = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <li>
                  <h6><strong><?= count($products_num); ?> products</strong></h6>
                </li>
              </ul>
              <a href="product_actions.php" class="btn btn-primary w-100">Show</a>
            </div>
          </div>
        </div>

        <!-- Cart -->
        <div class="col-md-4 col-lg-3">
          <div class="card card-pricing text-center h-100">
            <div class="card-header bg-primary text-white border-0">
              <h5 class="mb-0">Cart</h5>
            </div>
            <div class="card-body">
              <i class="fa-solid fa-basket-shopping fa-4x"></i>
              <ul class="list-unstyled mt-3 mb-4 small ">
                <?php
                $stmt = $connect->prepare("SELECT * FROM `cart`");
                $stmt->execute();
                $cart_num = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <li>
                  <h6><strong><?= count($cart_num); ?> cart</strong></h6>
                </li>
              </ul>
              <a href="cart_actions.php" class="btn btn-primary w-100">Show</a>
            </div>
          </div>
        </div>

        <!-- Orders -->
        <div class="col-md-4 col-lg-3">
          <div class="card card-pricing text-center h-100 ">
            <div class="card-header bg-primary text-white border-0">
              <h5 class="mb-0">Orders</h5>
            </div>
            <div class="card-body">
              <i class="fa-solid fa-truck fa-4x"></i>
              <ul class="list-unstyled mt-3 mb-4 small ">
                <?php
                $stmt = $connect->prepare("SELECT * FROM `orders`");
                $stmt->execute();
                $orders_num = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <li>
                  <h6><strong><?= count($orders_num); ?> orders</strong></h6>
                </li>
              </ul>
              <a href="orders_actions.php" class="btn btn-primary w-100">Show</a>
            </div>
          </div>
        </div>
      </div>
    </section>
  <?php endif; ?>
  <?php
  $stmt = $connect->prepare("SELECT * FROM `users`");
  $stmt->execute();

  if ($show == "users") :
  ?>
    <div class="container my-5">
      <?php
      if (isset($_SESSION['delete_message'])) {
        echo "<h4 class='center-alert bg-info text-white d-inline-block px-3 py-2 '>" . $_SESSION['delete_message'] . "</h4>";
        unset($_SESSION['delete_message']);
      }  ?>

      <!-- Table -->
      <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            unset($_SESSION['show_o']);
            while ($user_data = $stmt->fetch(PDO::FETCH_ASSOC)):
            ?>
              <tr>
                <td><?= $user_data['id']; ?></td>
                <td><?= $user_data['name']; ?></td>
                <td><?= $user_data['email']; ?></td>
                <td>
                  <?php if ($user_data['role'] === 'admin'): ?>
                    <span class="fw-bold text-primary">ADMIN</span>
                  <?php else: ?>
                    <form action="actions.php" method="post">
                      <a href="actions.php?action=view&user_id=<?= $user_data['id'] ?>" class="btn btn-sm btn-success"><i class="fa fa-search"></i></a>
                      <a href="dashboard.php?show=edit&user_id=<?= $user_data['id'] ?>" class="btn btn-sm btn-primary"><i class="fa fa-pen"></i></a>
                      <a href="actions.php?action=delete&user_id=<?= $user_data['id'] ?>" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                    </form>
                  <?php endif; ?>
                </td>
              </tr>
            <?php
            endwhile;
            ?>
          </tbody>
        </table>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <a href="dashboard.php?show=all" class="btn btn-sm btn-primary">Back</a>

          <a href="dashboard.php?show=add" class="btn btn-dark">
            <i class="fa fa-plus"></i> Add New
          </a>

        </div>

      <?php
    endif;
    if ($show == "view") :
      ?>
        <div class="container my-5">
          <table class="table table-bordered align-middle text-center ">
            <thead class="table-dark">
              <tr>
                
                <th>Order Id</th>
                <th>Total Price</th>
                <th>Created At</th>
                <th>Details</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($o_data as $order_data):
              ?>
                <tr>
               
                  <td><?= $order_data['id']; ?></td>
                  <td><?= $order_data['total_price']; ?></td>
                  <td><?= $order_data['created_at']; ?></td>
                  <td>
                    <a href="actions.php?show=details&order_id=<?= $order_data['id']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>
                  </td>
                </tr>
              <?php
              endforeach;
              ?>
            </tbody>
          </table>
          <a href="dashboard.php?show=users" class="btn btn-sm btn-primary">Back</a>
        </div>
      <?php
    endif;


    if ($show == "details"):
      ?>
        <div class="container my-5">
          <table class="table table-bordered align-middle text-center ">
            <thead class="table-dark">
              <tr>
                <th>Item ID</th>
                <th>Order Id</th>
                <th>product_id</th>
                <th>quantity</th>
                <th>Price</th>
                <th>name</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($items_data as $order_item):
              ?>
                <tr>
                  <td><?= $order_item['id']; ?></td>
                  <td><?= $order_item['order_id']; ?></td>
                  <td><?= $order_item['product_id']; ?></td>
                  <td><?= $order_item['quantity']; ?></td>
                  <td><?= $order_item['price']; ?></td>
                  <td><?= $order_item['name']; ?></td>
                </tr>
              <?php
              endforeach;
              ?>
            </tbody>
          </table>
          <a href="dashboard.php?show=view" class="btn btn-sm btn-primary">Back</a>
        </div>
      <?php
      unset($_SESSION['show_i']);
    endif;

    if ($show == "add") {
      ?>
        <div class="container my-5">
          <?php
          if (isset($_SESSION['pass_message'])) {
            echo "<h4 class='center-alert bg-info text-white d-inline-block px-3 py-2 '>" . $_SESSION['pass_message'] . "</h4>";
          }
          unset($_SESSION['pass_message']);
          ?>
          <form action="actions.php" method="post" class="contact-form row">
            <div class="form-field col-lg-6">
              <input name="name" id="name" class="input-text js-input" type="text">
              <label class="label" for="name">Name</label>
            </div>

            <div class="form-field col-lg-6">
              <input name="email" id="email" class="input-text js-input" type="email" required>
              <label class="label" for="email">E-mail</label>
            </div>

            <div class="form-field col-lg-6">
              <input name="pass" id="pass" class="input-text js-input" type="password">
              <label class="label" for="pass">Password</label>
            </div>

            <div class="form-field col-lg-6">
              <input name="confirm_pass" id="confirm_pass" class="input-text js-input" type="password">
              <label class="label" for="confirm_pass">Confirm Password</label>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
              <a href="dashboard.php?show=users" class="btn btn-sm btn-primary">Back</a>
              <div class="form-field ">
                <input class="submit-btn btn btn-primary" type="submit" value="Submit">
              </div>
            </div>
          </form>
        </div>
      <?php
    }


    if ($show == "edit") :
      ?>
        <div class="container my-5">
          <?php
          if (isset($_SESSION['edit_message'])) {
            echo "<h4 class='center-alert bg-info text-white d-inline-block px-3 py-2 '>" . $_SESSION['edit_message'] . "</h4>";
            unset($_SESSION['edit_message']);
          }
          ?>
          <form action="actions.php" method="post" class="contact-form row">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="user_id" value="<?= isset($_GET['user_id']) ? $_GET['user_id'] : "" ?> ">
            <div class="form-field col-lg-6">
              <input name="name" id="name" class="input-text js-input" type="text">
              <label class="label" for="name">Name</label>
            </div>

            <div class="form-field col-lg-6">
              <input name="email" id="email" class="input-text js-input" type="email" required>
              <label class="label" for="email">E-mail</label>
            </div>

            <div class="form-field col-lg-6">
              <input name="pass" id="pass" class="input-text js-input" type="password">
              <label class="label" for="pass">Password</label>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
              <a href="dashboard.php?show=users" class="btn btn-sm btn-primary">Back</a>
              <div class="form-field ">
                <input class="submit-btn btn btn-primary" type="submit" value="Submit">
              </div>
            </div>
          </form>
        </div>
      <?php
    endif;


      ?>
      <!-- Bootstrap JS (bundle includes Popper) -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>