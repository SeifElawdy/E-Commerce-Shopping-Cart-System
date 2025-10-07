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

<body>

    <!-- Navbar -->
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
                <a href="../index.php" class="navbar-text me-3 fs-4 fw-bold btn btn-success text-decoration-none">Home</a>
            </div>

            <!-- Left nav  -->
            <a href="dashboard.php?show=all" class="me-3 fs-5 btn btn-warning text-decoration-none">Dashboard</a>
            <a href="dashboard.php?show=users" class="me-3 fs-5 fw-bold text-decoration-none">Users</a>
            <a href="product_actions.php" class="me-3 fs-5 fw-bold text-decoration-none">Products</a>
            <a href="cart_actions.php" class="me-3 fs-5 fw-bold text-decoration-none">Cart</a>
            <a href="orders_actions.php" class="me-3 fs-5 fw-bold text-decoration-none">Orders</a>

            <?php if (isset($_SESSION['user_name'])): ?>
                <div class="d-flex align-items-center me-3">
                    <i class="bi bi-person-circle fs-1 me-2 text-success"></i>
                    <span class="fw-bold text-success fs-4"><?= htmlspecialchars($_SESSION['user_name']) ?></span> 
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="../login/logout.php" class="btn btn-danger">Logout</a>
            <?php endif; ?>
        </div>
        </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="admin-header">
        <div class="container">
            <h1 class="me-3 fs-1 fw-bold">Admin Dashboard</h1>
        </div>
    </header>