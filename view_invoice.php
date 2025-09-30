<?php
include 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
// Fetch invoice
$stmt = $pdo->prepare("SELECT * FROM invoices WHERE id=? AND business_id=?");
$stmt->execute([$id, $_SESSION['user_id']]);
$invoice = $stmt->fetch();
if (!$invoice) {
    die("Invoice not found");
}

// Fetch invoice items
$stmt = $pdo->prepare("SELECT * FROM invoice_items WHERE invoice_id=?");
$stmt->execute([$id]);
$items = $stmt->fetchAll();

// Fetch company info
$stmt = $pdo->prepare("SELECT business_name, email, address, phone FROM users WHERE id=?");
$stmt->execute([$_SESSION['user_id']]);
$company = $stmt->fetch();

include 'header.php';
?>

<div class="card my-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3>Invoice #<?= htmlspecialchars($invoice['invoice_id']) ?></h3>
    <a href="download_invoice.php?id=<?= $invoice['id'] ?>" class="btn btn-outline-primary">Download PDF</a>
  </div>
  <div class="card-body">
    <div class="mb-4">
      <h4 class="mb-1"><?= htmlspecialchars($company['business_name']) ?></h4>
      <p class="mb-0"><?= htmlspecialchars($company['address']) ?></p>
      <p class="mb-0">Email: <?= htmlspecialchars($company['email']) ?> | Phone: <?= htmlspecialchars($company['phone']) ?></p>
    </div>
    <hr>
    <div class="mb-3">
      <strong>Customer:</strong> <?= htmlspecialchars($invoice['customer_name']) ?> (<?= htmlspecialchars($invoice['customer_phone']) ?>)<br>
      <strong>Purchase Date:</strong> <?= htmlspecialchars($invoice['date']) ?>
    </div>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Description</th>
          <th>Qty</th>
          <th>Unit Cost</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $i => $item): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td><?= htmlspecialchars($item['description']) ?></td>
          <td><?= $item['quantity'] ?></td>
          <td>&#8358;<?= number_format($item['unit_cost'], 2) ?></td>
          <td>&#8358;<?= number_format($item['amount'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div class="text-end mt-3">
      <strong>Total:</strong> &#8358;<?= number_format($invoice['total_amount'], 2) ?>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
