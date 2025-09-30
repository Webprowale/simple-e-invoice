<?php include 'config.php'; if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; } include 'header.php';
$stmt = $pdo->prepare("SELECT * FROM invoices WHERE business_id=? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$invoices = $stmt->fetchAll();
?>
<h2>Past Invoices</h2>
<table class="table">
  <tr><th>Invoice ID</th><th>Customer</th><th>Total</th><th>Date</th><th>Action</th></tr>
  <?php foreach($invoices as $inv): ?>
  <tr>
    <td><?= $inv['invoice_id'] ?></td>
    <td><?= $inv['customer_name'] ?></td>
    <td><?= $inv['total_amount'] ?></td>
    <td><?= $inv['date'] ?></td>
    <td><a href="view_invoice.php?id=<?= $inv['id'] ?>" class="btn btn-sm btn-info">View</a></td>
  </tr>
  <?php endforeach; ?>
</table>
<?php include 'footer.php'; ?>
