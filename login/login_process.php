<?php
session_start();
require "../connection/db_con.php";
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<style>
    .center-alert {
        width: 60%;
        padding: 40px;
        margin: 100px auto;
        border-radius: 10px;
        text-align: center;
    }
</style>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $pass = $_POST['password'];

        $stmt = $connect->prepare("SELECT * FROM `users` WHERE `users`.`email` = ? ");
        $stmt->execute([$email]);

        if ($stmt->rowCount() === 1) {

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($pass, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header("Location: ../Admin/dashboard.php");
                } else {
                    header("Location: ../index.php");
                }
                exit();
            } else {
?>
                <div class="center-alert bg-danger text-white">
                    <div>
                        <h1>Wrong Password</h1>
                    </div>
                </div>
            <?php
                header("Refresh: 3; URL=login.html");
                exit();
            }
        } else {
            ?>
            <div class="center-alert bg-danger text-white">
                <div>
                    <h1>Wrong email</h1>
                </div>
            </div>
        <?php
            header("Refresh: 3; URL=login.html");
            exit();
        }
    } else {
        ?>
        <div class="center-alert bg-danger text-white">
            <div>
                <h1>Enter email or password</h1>
            </div>
        </div>
<?php
        header("Refresh: 3; URL=login.html");
        exit();
    }
}
