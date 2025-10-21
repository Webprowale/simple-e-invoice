<?php
include 'config.php';
session_start();

$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE users 
        SET business_name=?, tagline=?, address=?, phone=?, email=?, username=? 
        WHERE id=?");
    try {
        $stmt->execute([
            $_POST['business_name'],
            $_POST['tagline'],
            $_POST['address'],
            $_POST['phone'],
            $_POST['email'],
            $_POST['username'],
            $_SESSION['user_id']
        ]);
        $success = "Profile updated successfully!";
    } catch (PDOException $e) {
        $error = "Error updating profile: " . $e->getMessage();
    }
}


$stmt = $pdo->prepare("SELECT business_name, tagline, address, phone, email, username 
                       FROM users WHERE id=?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();


// echo "<pre>";
// print_r($user);
// echo "</pre>";
// exit;

include 'header.php';
?>

<div class="row justify-content-center">
  <div class="col-md-8 col-lg-6">
    <div class="card">
      <div class="card-body">
        <h3 class="mb-4 text-center">My Profile</h3>
        <?php if ($success): ?>
          <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
          <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
          <div class="mb-3">
            <label class="form-label">Business Name</label>
            <input type="text" class="form-control" name="business_name" 
                   value="<?= htmlspecialchars($user['business_name'] ?? '') ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Tagline</label>
            <input type="text" class="form-control" name="tagline"
                   value="<?= htmlspecialchars($user['tagline'] ?? '') ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text" class="form-control" name="address"
                   value="<?= htmlspecialchars($user['address'] ?? '') ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" class="form-control" name="phone" 
                   value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" 
                   value="<?= htmlspecialchars($user['email'] ?? '') ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" name="username" 
                   value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Update Profile</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
