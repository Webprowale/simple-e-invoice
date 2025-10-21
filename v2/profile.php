<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include_once __DIR__ . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE users 
        SET business_name=?, email=?, username=?, tagline=?, address=?, phone=?
        WHERE id=?");
    try {
        $stmt->execute([
            $_POST['business_name'],
            $_POST['email'],
            $_POST['username'],
            $_POST['tagline'],
            $_POST['address'],
            $_POST['phone'],
            $_SESSION['user_id']
        ]);
        $_SESSION['flash_message'] = [
            'type' => 'success',
            'message' => 'Profile updated successfully!'
        ];
    } catch (PDOException $e) {
        $_SESSION['flash_message'] = [
            'type' => 'error',
            'message' => 'Error updating profile: ' . $e->getMessage()
        ];
    }
    header("Location: profile.php");
    exit;
}

$stmt = $pdo->prepare("SELECT business_name, email, username, tagline, address, phone FROM users WHERE id=?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
include 'includes/header.php';
?>

<div class="main-content container-fluid">
    <div class="page-title">
        <h3>My Profile</h3>
        <p class="text-subtitle text-muted">Update your business and account information.</p>
    </div>
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body pt-4">
                        <form method="POST" class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label">Business Name</label>
                                <input type="text" class="form-control" name="business_name" 
                                    value="<?= htmlspecialchars($user['business_name'] ?? '') ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" 
                                    value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">Tagline</label>
                                <input type="text" class="form-control" name="tagline"
                                    value="<?= htmlspecialchars($user['tagline'] ?? '') ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="address"
                                    value="<?= htmlspecialchars($user['address'] ?? '') ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone"
                                    value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" 
                                    value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary float-right mt-3">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>