<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare(
        "INSERT INTO users (business_name, tagline, address, phone, email, username, password) VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $hashed = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $stmt->execute([
        $_POST['business_name'],
        $_POST['tagline'],
        $_POST['address'],
        $_POST['phone'],
        $_POST['email'],
        $_POST['username'],
        $hashed
    ]);
    $newUserId = $pdo->lastInsertId();
    $_SESSION['user_id'] = $newUserId;
    header("Location: profile.php");
    exit;
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simple e-Invoice | Register</title>
    <link rel="stylesheet" href="./assets/css/styles.min.css" />
</head>

<body>
    <!-- Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
         data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <div class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <h2 class="text-center mb-5">Simple e-Invoice</h2>
                                <form method="POST">
                                    <input class="form-control mb-3" name="business_name" placeholder="Business Name" required>
                                    <input class="form-control mb-3" name="tagline" placeholder="Tagline">
                                    <input class="form-control mb-3" name="address" placeholder="Address">
                                    <input class="form-control mb-3" name="phone" placeholder="Phone">
                                    <input class="form-control mb-3" name="email" placeholder="Email">
                                    <input class="form-control mb-3" name="username" placeholder="Username" required>
                                    <input class="form-control mb-3" type="password" name="password" placeholder="Password" required>
                                    <button class="btn btn-primary col-12 mb-2">Register</button>
                                </form>
                                <div class="d-flex align-items-center justify-content-center">
                                    <p class="fs-4 mb-0 fw-bold">Already have an Account?</p>
                                    <a class="text-primary fw-bold ms-2" href="login.php">Login</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="./assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="./assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- solar icons -->
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>
