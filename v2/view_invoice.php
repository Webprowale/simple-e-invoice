<?php
session_start();
include_once __DIR__ . '/includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Invoice ID is required.");
}

$id = $_GET['id'];

// Fetch invoice
$stmt = $pdo->prepare("SELECT * FROM invoices WHERE id=? AND business_id=?");
$stmt->execute([$id, $_SESSION['user_id']]);
$invoice = $stmt->fetch();

if (!$invoice) {
    die("Invoice not found or you don't have permission to view it.");
}

// Fetch invoice items
$stmt = $pdo->prepare("SELECT * FROM invoice_items WHERE invoice_id=?");
$stmt->execute([$id]);
$items = $stmt->fetchAll();

// Fetch company info
$stmt = $pdo->prepare("SELECT business_name, email, address, phone, tagline FROM users WHERE id=?");
$stmt->execute([$_SESSION['user_id']]);
$company = $stmt->fetch();

include 'includes/header.php';
?>

<div class="main-content container-fluid">
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Invoice #<?php echo htmlspecialchars($invoice['invoice_id']); ?></h4>
                <a href="download_invoice.php?id=<?php echo $invoice['id']; ?>" id="download-btn" class="btn btn-primary">Download PDF</a>
            </div>
            <div class="card-body">
                <div class="row mb-4 gy-3">
                    <div class="col-md-6">
                        <h5 class="text-bold"><?php echo htmlspecialchars($company['business_name']); ?></h5>
                        <p class="text-muted">
                            <?php echo htmlspecialchars($company['address']); ?><br>
                            Email: <?php echo htmlspecialchars($company['email']); ?><br>
                            Phone: <?php echo htmlspecialchars($company['phone']); ?>
                        </p>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <strong>To:</strong> <?php echo htmlspecialchars($invoice['customer_name']); ?> (<?php echo htmlspecialchars($invoice['customer_phone']); ?>)<br>
                        <strong>Date:</strong> <?php echo htmlspecialchars($invoice['date']); ?>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Description</th>
                                <th class="text-right">Qty</th>
                                <th class="text-right">Unit Cost</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $i => $item): ?>
                            <tr>
                                <td><?php echo $i + 1; ?></td>
                                <td><?php echo htmlspecialchars($item['description']); ?></td>
                                <td class="text-right"><?php echo $item['quantity']; ?></td>
                                <td class="text-right">&#8358;<?php echo number_format($item['unit_cost'], 2); ?></td>
                                <td class="text-right">&#8358;<?php echo number_format($item['amount'], 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-end mt-4">
                    <div class="col-md-4 text-right">
                        <h5 class="text-bold">Total: &#8358;<?php echo number_format($invoice['total_amount'], 2); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>

<script>
document.getElementById('download-btn').addEventListener('click', function(e) {
    e.preventDefault();
    const downloadUrl = this.href;
    Swal.fire({
        title: 'Preparing Download...',
        text: 'Your invoice will begin downloading shortly.',
        icon: 'info',
        showConfirmButton: false,
        timer: 2000
    }).then(() => {
        window.location.href = downloadUrl;
    });
});
</script>