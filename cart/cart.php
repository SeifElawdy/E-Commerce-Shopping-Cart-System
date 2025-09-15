<?php 
session_start();
require "../connection/db_con.php";

if(!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.html");
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <title>bs4 cart - Bootdey.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
    	body{
    margin-top:20px;
    background:#eee;
}
.ui-w-40 {
    width: 40px !important;
    height: auto;
}

.card{
    box-shadow: 0 1px 15px 1px rgba(52,40,104,.08);    
}

.ui-product-color {
    display: inline-block;
    overflow: hidden;
    margin: .144em;
    width: .875rem;
    height: .875rem;
    border-radius: 10rem;
    -webkit-box-shadow: 0 0 0 1px rgba(0,0,0,0.15) inset;
    box-shadow: 0 0 0 1px rgba(0,0,0,0.15) inset;
    vertical-align: middle;
}
    </style>
</head>
<body>
<div class="container px-3 my-5 clearfix">
    <!-- Shopping cart table -->
    <div class="card">
        <div class="card-header">
            <h2>Shopping Cart</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
              <!-- --------------------------------------------------------------------------- -->
              <table class="table table-bordered m-0">
                <thead>
                  <tr>
                    <!-- Set columns width -->
                    <th class="text-center py-3 px-4" style="min-width: 400px;">Product Name</th>
                    <th class="text-right py-3 px-4" style="width: 100px;">Price</th>
                    <th class="text-center py-3 px-4" style="width: 120px;">Quantity</th>
                    <th class="text-right py-3 px-4" style="width: 100px;">Total</th>
                    <th class="text-center align-middle py-3 px-0" style="width: 40px;"><a href="#" class="shop-tooltip float-none text-light" title="" data-original-title="Clear cart"><i class="ino ion-md-trash"></i></a></th>
                  </tr>
                </thead>
                <tbody>
                  <!-- --------------php------------------ -->
                  <?php 
                  
                  if(isset($_SESSION['user_id'])){
                    $user_id=$_SESSION['user_id'];
                  }else{
                    header("Location:/login/login.html");
                    exit();
                  }
                  $stmt=$connect->prepare("SELECT products.id, products.name, products.price,
                  products.image, cart.quantity FROM cart
                  JOIN products ON cart.product_id = products.id
                  WHERE cart.user_id = ?");
                  $stmt->execute([$user_id]);
                  
                  $total=0;
                  while($item=$stmt->fetch(PDO::FETCH_ASSOC)):
                  ?>
                  <tr>
                    <td class="p-4">
                      <div class="media align-items-center">
                        <img src="<?="../".htmlspecialchars($item['image']) ?>" class="d-block ui-w-40 ui-bordered mr-4" alt="<?=htmlspecialchars($item['name']) ?>">
                        <div class="media-body">
                          <a href="#" class="d-block text-dark"><?=htmlspecialchars($item['name']) ?></a>
                        </div>
                      </div>
                    </td>
                    <td class="text-right font-weight-semibold align-middle p-4">$<?=htmlspecialchars($item['price']) ?></td>
                    <td class="text-right font-weight-semibold align-middle p-4"><strong><?=htmlspecialchars($item['quantity']) ?> </strong>
                    <br>
                    <hr>
                    <form action="cart_process.php" method="post">
                    <input type="hidden" name="product_ID" value="<?=$item['id']?>">
                    <input type="submit" name="add" value="ADD" >
                    <input type="submit" name="del" value="DEL" >
                  </form>
                  </td>
                    <td class="text-right font-weight-semibold align-middle p-4">$<?=htmlspecialchars($item['price']*$item['quantity']) ?></td>
                    <?php $item_total= htmlspecialchars($item['price']*$item['quantity']);
                    $total+=$item_total;
                    
                    $product_id=$item['id'];
                    $_SESSION['product_id']=$product_id;
                    ?>
                    <td class="text-center align-middle px-0"><a href="cart_process.php?delete=true" class="shop-tooltip close float-none text-danger" title="" data-original-title="Remove">×</a></td>
                  </tr>
                <?php endwhile; ?>
                </tbody>
              </table>
            </div>
            <!-- / Shopping cart table -->
            <div class="d-flex flex-wrap justify-content-between align-items-center pb-4">
              
              <div class="float-right ">
                <div class="text-right mt-4">
                  <label class="text-muted font-weight-normal m-0">Total price</label>
                  <div>
                    <?php $_SESSION['total_price']=$total;?>
                  <strong>$<?=$total?></strong></div>
                </div>
              </div>
            </div>
        
            <div class="float-right">
              <form action="cart_process.php" method="post">
              <input type="submit" class="btn btn-lg btn-default md-btn-flat mt-2 mr-3" name="back" value="Back to shopping">
              <input type="submit" class="btn btn-lg btn-primary mt-2" name="checkout" value="Checkout">
              </form>
            </div>
        
          </div>
      </div>
  </div>
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.1/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
	
</script>
</body>
</html>