<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include('includes/header.php');
$stmt = $pdo->prepare("SELECT COUNT(*) as count, COALESCE(SUM(total_amount),0) as total FROM invoices WHERE business_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$stats = $stmt->fetch();
$stmt = $pdo->prepare("SELECT * FROM invoices WHERE business_id=? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$_SESSION['user_id']]);
$recent_invoices = $stmt->fetchAll();
?>

<div class="main-content container-fluid">
    <div class="page-title">
        <h3>Dashboard</h3>
    </div>

    <section class="section">
        <div class="row mb-2">
            <div class="col-12 col-md-6">
                <div class="card card-statistic">
                    <div class="card-body p-0">
                        <div class="d-flex flex-column">
                            <div class='px-3 py-3 d-flex justify-content-between'>
                                <h3 class='card-title'>TOTAL INVOICES</h3>
                                <div class="card-right d-flex align-items-center">
                                    <p><?php echo $stats['count']; ?></p>
                                </div>
                            </div>
                            <div class="chart-wrapper">
                                <canvas id="canvas1" style="height:100px !important"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card card-statistic">
                    <div class="card-body p-0">
                        <div class="d-flex flex-column">
                            <div class='px-3 py-3 d-flex justify-content-between'>
                                <h3 class='card-title'>REVENUE</h3>
                                <div class="card-right d-flex align-items-center">
                                    <p>&#8358;<?php echo number_format($stats['total'], 2); ?></p>
                                </div>
                            </div>
                            <div class="chart-wrapper">
                                <canvas id="canvas2" style="height:100px !important"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

       
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Recent Invoices</h4>
                    </div>

                    <div class="card-body px-0 pb-0">
                        <div class="table-responsive">
                            <?php if (!empty($recent_invoices)): ?>
                                <table class='table mb-0' id="table1">
                                    <thead>
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Amount (₦)</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recent_invoices as $invoice): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($invoice['customer_name']); ?></td>
                                                <td>&#8358;<?php echo number_format($invoice['total_amount'], 2); ?></td>
                                                <td><?php echo htmlspecialchars($invoice['date']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="p-4 text-center">
                                    <p class="text-muted mb-0">No invoices found.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include('includes/footer.php'); ?>
