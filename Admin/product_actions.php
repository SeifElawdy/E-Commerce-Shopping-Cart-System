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
    if (isset($_GET['show'])) {
        $show = $_GET['show'];
    }
}
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['show'])) {
        $show = $_POST['show'];
    }
}
?>




<!-- Table -->
<?php
if ($show == "all"):
    $stmt = $connect->prepare("SELECT * FROM `products` ;");
    $stmt->execute();
?>
    <div class="container my-5">
        <?php
        if (isset($_SESSION['del_message'])) {
            echo "<h4 class='center-alert bg-info text-white d-inline-block px-3 py-2 '>" . $_SESSION['del_message'] . "</h4>";
            unset($_SESSION['del_message']);
        }
        ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($product_data = $stmt->fetch(PDO::FETCH_ASSOC)):
                    ?>
                        <tr>
                            <td><?= $product_data['id']; ?></td>
                            <td><?= $product_data['name']; ?></td>
                            <td><?= $product_data['price']; ?></td>
                            <td><img src="../<?= $product_data['image'] ?>" class="img-thumbnail" style="width:90px; height:100px; object-fit:cover;"></td>
                            <td>
                                <a href="?show=edit&product_id=<?= $product_data['id'] ?>" class="btn btn-sm btn-primary"><i class="fa fa-pen"></i></a>
                                <a href="?show=delete&product_id=<?= $product_data['id'] ?>" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>

                            </td>
                        </tr>
                    <?php
                    endwhile;
                    ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="dashboard.php?show=all" class="btn btn-sm btn-primary">Back</a>
                <a href="?show=add" class="btn btn-dark">
                    <i class="fa fa-plus"></i> Add New
                </a>
            </div>
        </div>
    <?php
endif;
if ($show == "add"):
    ?>
        <div class="container my-5">
            <?php
            if (isset($_SESSION['message'])) {
                echo "<h4 class='center-alert bg-info text-white d-inline-block px-3 py-2 '>" . $_SESSION['message'] . "</h4>";
                unset($_SESSION['message']);
            }
            ?>
            <form action="product_actions.php" method="post" enctype="multipart/form-data" class="contact-form row">
                <input type="hidden" name="show" value="addQ">

                <div class="form-field col-lg-6">
                    <input name="name" id="name" class="input-text js-input" type="text">
                    <label class="label" for="name">Name</label>
                </div>

                <div class="form-field col-lg-6">
                    <input name="price" id="price" class="input-text js-input" type="number">
                    <label class="label" for="price">Price</label>
                </div>

                <div class="form-field col-lg-6">
                    <label class="label" for="image">Image</label>
                    <div class="file-input-wrapper">
                        <span class="file-input-label">Choose File</span>
                        <span class="file-input-text">No file chosen</span>
                        <input type="file" name="image" id="image" accept="image/*" onchange="updateFileName(this)">
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="?show=all" class="btn btn-sm btn-primary">Back</a>
                    <div class="form-field ">
                        <input class="submit-btn btn btn-primary" type="submit" value="Submit">
                    </div>
                </div>
            </form>
        </div>
    <?php
//////////////
endif;
if ($show == "addQ") {
    if (isset($_POST['name']) && isset($_POST['price'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $image = null;

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $targetDir = "img/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileName = basename($_FILES["image"]["name"]);
            $targetFile = $targetDir . $fileName;

            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                    $image = $targetFile;
                }
            }
        }

        if ($image) {
            $stmt = $connect->prepare("INSERT INTO `products` (`name`,`price`,`image`) VALUES (?,?,?);");
            $stmt->execute([$name, $price, $image]);
        } else {
            $stmt = $connect->prepare("INSERT INTO `products` (`name`,`price`) VALUES (?,?);");
            $stmt->execute([$name, $price]);
        }

        $_SESSION['message'] = "Add Successfully";
        header("Location:product_actions.php?show=add");
        exit;
    }
}

if ($show == "edit"):
    ?>
        <div class="container my-5">
            <?php
            if (isset($_SESSION['$u_message'])) {
                echo "<h4 class='center-alert bg-info text-white d-inline-block px-3 py-2 '>" . $_SESSION['$u_message'] . "</h4>";
                unset($_SESSION['$u_message']);
            }
            ?>
            <form action="product_actions.php" method="post" enctype="multipart/form-data" class="contact-form row">
                <input type="hidden" name="show" value="editQ">
                <input type="hidden" name="product_id" value="<?= $product_id ?>">

                <div class="form-field col-lg-6">
                    <input name="name" id="name" class="input-text js-input" type="text">
                    <label class="label" for="name">Name</label>
                </div>

                <div class="form-field col-lg-6">
                    <input name="price" id="price" class="input-text js-input" type="number">
                    <label class="label" for="price">Price</label>
                </div>

                <div class="form-field col-lg-6">
                    <label class="label" for="image">Image</label>
                    <div class="file-input-wrapper">
                        <span class="file-input-label">Choose File</span>
                        <span class="file-input-text">No file chosen</span>
                        <input type="file" name="image" id="image" accept="image/*" onchange="updateFileName(this)">
                    </div>
                </div>


                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="?show=all" class="btn btn-sm btn-primary">Back</a>
                    <div class="form-field ">
                        <input class="submit-btn btn btn-primary" type="submit" value="Submit">
                    </div>
                </div>
            </form>
        </div>
    <?php
endif;

if ($show == "editQ"):
    if (isset($_POST['name']) && isset($_POST['price']) && isset($_POST['product_id'])) {

        $name = $_POST['name'];
        $price = $_POST['price'];
        $product_id = $_POST['product_id'];
        $image = null;


        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $targetDir = "img/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileName = basename($_FILES["image"]["name"]);
            $targetFile = $targetDir . $fileName;


            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                    $image = $targetFile;
                }
            }
        }


        if ($image) {
            $stmt = $connect->prepare("UPDATE `products` SET `name`=? , `price`=? , `image`=?  WHERE `id`=?;");
            $stmt->execute([$name, $price, $image, $product_id]);
        } else {
            $stmt = $connect->prepare("UPDATE `products` SET `name`=? , `price`=? WHERE `id`=?;");
            $stmt->execute([$name, $price, $product_id]);
        }

        $_SESSION['$u_message'] = "Updated Successfully";
        header("Location:product_actions.php?show=edit");
        exit;
    } else {
        $_SESSION['$u_message'] = "Enter all the fields!!";
        header("Location:product_actions.php?show=edit");
        exit;
    }
endif;
/////////////////////////Delete section////////////////////////

if ($show == "delete") :
    if (isset($_GET['product_id'])) {
        $product_id = $_GET['product_id'];

        $stmt = $connect->prepare("DELETE FROM `products` WHERE `id`=?");
        $stmt->execute([$product_id]);
        $_SESSION['del_message'] = "Deleted Successfully";
        header("Location:product_actions.php?show=all");
    }

endif;



///////////////////////////End Delete//////////////////////////
    ?>
    <script>
        function updateFileName(input) {
            let fileName = input.files.length > 0 ? input.files[0].name : "No file chosen";
            input.parentElement.querySelector(".file-input-text").textContent = fileName;
        }
    </script>

    </html>