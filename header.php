<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Simple e-Invoice</title>
  <link rel="stylesheet" href="./assets/css/styles.min.css" />
</head>
<body>
  <!-- Page Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
       data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    
    <!-- Top bar -->
    <div class="app-topstrip bg-dark py-6 px-3 w-100 d-lg-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center justify-content-center gap-5 mb-2 mb-lg-0">
        <a class="d-flex justify-content-center" href="#" target="_blank">
          <img src="./assets/images/logos/logo-wrappixel.svg" alt="" width="150">
        </a>
      </div>
      <div class="d-lg-flex align-items-center gap-2">
        <h3 class="text-white fs-5 text-center mb-0">
          Welcome on board!
          <span class="text-primary fw-bold">
          <?php
            if (isset($_SESSION['user_id'])) {
                include 'config.php';
                $stmt = $pdo->prepare("SELECT business_name FROM users WHERE id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $user = $stmt->fetch();
                if ($user && !empty($user['business_name'])) {
                    echo htmlspecialchars($user['business_name']);
                }
            }
?>
          </span>
        </h3>
      </div>
    </div>

    <!-- Sidebar -->
    <aside class="left-sidebar">
      <div class="brand-logo d-flex align-items-center justify-content-between">
        <h4>Simple e-Invoice</h4>
        <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
          <i class="ti ti-x fs-8"></i>
        </div>
      </div>
      <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
        <ul id="sidebarnav">
          <li class="nav-small-cap">Home</li>
          <li class="sidebar-item">
            <a class="sidebar-link primary-hover-bg" href="dashboard.php">Dashboard</a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link primary-hover-bg" href="invoices_list.php">View Invoices</a>
          </li>
          <?php if (isset($_SESSION['user_id'])): ?>
            <li class="sidebar-item"><a class="sidebar-link" href="logout.php">Logout</a></li>
          <?php else: ?>
            <li class="sidebar-item"><a class="sidebar-link" href="login.php">Login</a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="register.php">Register</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </aside>

    <!-- Body Wrapper Start -->
    <div class="body-wrapper">
      <div class="body-wrapper-inner">
        <div class="container-fluid">
