<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'header.php';

$stmt = $pdo->prepare("SELECT COUNT(*) as count, COALESCE(SUM(total_amount),0) as total FROM invoices WHERE business_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$stats = $stmt->fetch();
?>

<h2>Dashboard</h2>
<div class="row mb-4">
  <div class="col-12 d-flex justify-content-end">
    <a href="create_invoice.php" class="btn btn-success">Create New Invoice</a>
  </div>
</div>
<div class="row">
  <div class="col-md-6 col-lg-4 mb-3">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title">Total Invoices</h5>
        <p class="display-6 fw-bold"><?php echo $stats['count']; ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-lg-4 mb-3">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title">Total Amount</h5>
        <p class="display-6 fw-bold">&#8358;<?php echo number_format($stats['total'], 2); ?></p>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
