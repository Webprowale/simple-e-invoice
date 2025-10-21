<?php
session_start();
include_once __DIR__ . '/includes/config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'includes/header.php';

$stmt = $pdo->prepare("SELECT * FROM invoices WHERE business_id=? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$invoices = $stmt->fetchAll();
?>

<div class="main-content container-fluid">
    <div class="page-title">
        <h3>Past Invoices</h3>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr><th>Invoice #</th><th>Customer</th><th>Total</th><th>Date</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($invoices as $inv): ?>
                            <tr>
                                <td><?= htmlspecialchars($inv['invoice_id']) ?></td>
                                <td><?= htmlspecialchars($inv['customer_name']) ?></td>
                                <td>&#8358;<?= number_format($inv['total_amount'], 2) ?></td>
                                <td><?= htmlspecialchars($inv['date']) ?></td>
                                <td><a href="view_invoice.php?id=<?= $inv['id'] ?>" class="btn btn-sm btn-info">View</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>