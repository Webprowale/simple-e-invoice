<?php
session_start();
include_once __DIR__ . '/includes/config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare(
        "INSERT INTO users (business_name, email, username, password) VALUES (?, ?, ?, ?)"
    );
    $hashed = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $stmt->execute([
        $_POST['business_name'],
        $_POST['email'],
        $_POST['username'],
        $hashed
    ]);
    $newUserId = $pdo->lastInsertId();
    $_SESSION['user_id'] = $newUserId;
    $_SESSION['flash_message'] = [
        'type' => 'success',
        'message' => 'Registration successful! Please complete your profile.'
    ];
    header("Location: profile.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up - Simple E-Invoice</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/app.css">
</head>

<body>
    <div id="auth">
        
<div class="container">
    <div class="row">
        <div class="col-md-7 col-sm-12 mx-auto">
            <div class="card pt-4">
                <div class="card-body">
                    <div class="text-center mb-5">
                       <h1>Simple E-Invoice</h1>
                       
                        <p>Please fill the form to register.</p>
                    </div>
                    <form method="POST" action="register.php">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="business-name-column">Business Name</label>
                                    <input type="text" id="business-name-column" class="form-control"  name="business_name" required>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="email-id-column">Email</label>
                                    <input type="email" id="email-id-column" class="form-control" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="username-column">Username</label>
                                    <input type="text" id="username-column" class="form-control" name="username" required>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="password-column">Password</label>
                                    <input type="password" id="password-column" class="form-control" name="password" required>
                                </div>
                            </div>
                        </diV>

                                <a href="login.php">Have an account? Login</a>
                        <div class="clearfix">
                            <button class="btn btn-primary float-right">Submit</button>
                        </div>
                    </form>
                   
                </div>
            </div>
        </div>
    </div>
</div>

    </div>
    <script src="assets/js/feather-icons/feather.min.js"></script>
    <script src="assets/js/app.js"></script>
    
    <script src="assets/js/main.js"></script>
</body>

</html>
