<?php

require_once __DIR__ . '/../vendor/autoload.php';

session_start();
include_once __DIR__ . '/includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM invoices WHERE id=? AND business_id=?");
$stmt->execute([$id, $_SESSION['user_id']]);
$invoice = $stmt->fetch();
if (!$invoice) {
    die("Invoice not found");
}

$stmt = $pdo->prepare("SELECT * FROM invoice_items WHERE invoice_id=?");
$stmt->execute([$id]);
$items = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT business_name, email, address, phone FROM users WHERE id=?");
$stmt->execute([$_SESSION['user_id']]);
$company = $stmt->fetch();

$html = '
<style>
    body { font-family: Arial, sans-serif; font-size: 12px; }
    .header { text-align: center; margin-bottom: 20px; }
    .company { font-size: 18px; font-weight: bold; }
    .contact { font-size: 12px; }
    .invoice-title { font-size: 22px; margin-bottom: 20px; text-align: center; }
    .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .table th, .table td { border: 1px solid #333; padding: 8px; text-align: left; }
    .table th { background: #eee; }
    .text-right { text-align: right; }
    .total { text-align: right; font-size: 16px; font-weight: bold; margin-top: 20px; }
</style>
<div class="header">
    <div class="company">' . htmlspecialchars($company['business_name']) . '</div>
    <div class="contact">
        ' . htmlspecialchars($company['address']) . '<br>
        Email: ' . htmlspecialchars($company['email']) . ' | Phone: ' . htmlspecialchars($company['phone']) . '
    </div>
</div>
<div class="invoice-title">Invoice #' . htmlspecialchars($invoice['invoice_id']) . '</div>
<div>
    <strong>Customer:</strong> ' . htmlspecialchars($invoice['customer_name']) . ' (' . htmlspecialchars($invoice['customer_phone']) . ')<br>
    <strong>Date:</strong> ' . htmlspecialchars($invoice['date']) . '
</div>
<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Description</th>
            <th class="text-right">Qty</th>
            <th class="text-right">Unit Cost</th>
            <th class="text-right">Amount</th>
        </tr>
    </thead>
    <tbody>';
foreach ($items as $i => $item) {
    $html .= '<tr>
        <td>' . ($i + 1) . '</td>
        <td>' . htmlspecialchars($item['description']) . '</td>
        <td class="text-right">' . $item['quantity'] . '</td>
        <td class="text-right">&#8358;' . number_format($item['unit_cost'], 2) . '</td>
        <td class="text-right">&#8358;' . number_format($item['amount'], 2) . '</td>
    </tr>';
}
$html .= '
    </tbody>
</table>
<div class="total">
    Total: &#8358;' . number_format($invoice['total_amount'], 2) . '
</div>';

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$filename = "Invoice_" . $invoice['invoice_id'] . ".pdf";
$mpdf->Output($filename, 'D');
exit;
