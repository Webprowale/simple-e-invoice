<?php

include_once __DIR__ . '/includes/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
if (
    empty($_POST['customer_name']) ||
    empty($_POST['total_amount']) ||
    empty($_POST['description']) ||
    empty($_POST['quantity']) ||
    empty($_POST['unit_cost'])
) {

    header("Location: create_invoice.php?error=missing_fields");
    exit;
}

$invoice_id = "INV-" . date("YmdHis");
$stmt = $pdo->prepare("INSERT INTO invoices (invoice_id, business_id, customer_name, customer_phone, date, total_amount) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([
    $invoice_id,
    $_SESSION['user_id'],
    $_POST['customer_name'],
    $_POST['customer_phone'],
    date('Y-m-d'),
    $_POST['total_amount']
]);
$invoice_db_id = $pdo->lastInsertId();

foreach ($_POST['description'] as $i => $desc) {
    $qty = $_POST['quantity'][$i];
    $unit = $_POST['unit_cost'][$i];
    $amount = $qty * $unit;
    $stmt = $pdo->prepare("INSERT INTO invoice_items (invoice_id, description, quantity, unit_cost, amount) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$invoice_db_id, $desc, $qty, $unit, $amount]);
}

$_SESSION['flash_message'] = [
    'type' => 'success',
    'message' => 'Invoice created successfully!'
];
header("Location: invoices_list.php");
exit;
