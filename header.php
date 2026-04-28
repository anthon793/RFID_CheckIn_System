<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='16' fill='%2315558a'/%3E%3Cpath d='M32 14c-9.4 0-17 7.6-17 17v6' fill='none' stroke='white' stroke-width='4' stroke-linecap='round'/%3E%3Cpath d='M23 47V31c0-5 4-9 9-9s9 4 9 9v3' fill='none' stroke='white' stroke-width='4' stroke-linecap='round'/%3E%3Cpath d='M32 30v20' fill='none' stroke='white' stroke-width='4' stroke-linecap='round'/%3E%3Cpath d='M41 43c4-3 6-7 6-12 0-8.3-6.7-15-15-15' fill='none' stroke='white' stroke-width='4' stroke-linecap='round' opacity='.82'/%3E%3C/svg%3E">

  <!-- Google Font: Inter -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
  <style>
    :root {
      --app-ink: #1f2937;
      --app-muted: #6b7280;
      --app-border: #e5e7eb;
      --app-surface: #ffffff;
      --app-soft: #f8fafc;
      --app-primary: #15558a;
      --app-primary-soft: #e8f2fb;
      --sidebar-bg: #103c5b;
      --sidebar-hover: #1d658c;
      --sidebar-active: #f8fafc;
      --sidebar-muted: #a9c2d4;
    }

    body {
      color: var(--app-ink);
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      font-size: 14px;
      letter-spacing: 0;
    }

    h1 {
      font-size: 1.75rem;
      letter-spacing: 0;
    }

    h3,
    .card-title {
      font-size: 1.02rem;
      letter-spacing: 0;
    }

    .content-wrapper {
      background: var(--app-soft);
    }

    .main-header {
      background: var(--app-surface);
      border-bottom: 1px solid var(--app-border);
      min-height: 76px;
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }

    .main-header .nav-link {
      align-items: center;
      border-radius: 10px;
      color: #64748b;
      display: inline-flex;
      height: 40px;
      justify-content: center;
      min-width: 40px;
    }

    .main-header .nav-link:hover {
      background: #f1f5f9;
      color: var(--app-primary);
    }

    .main-sidebar {
      background: var(--sidebar-bg) !important;
      border-right: 0;
      box-shadow: 8px 0 24px rgba(15, 23, 42, .08) !important;
    }

    .brand-link {
      align-items: center;
      border-bottom: 1px solid rgba(255, 255, 255, .12) !important;
      color: #ffffff !important;
      display: flex;
      gap: .75rem;
      min-height: 84px;
      padding: 1rem 1.25rem;
    }

    .brand-mark {
      align-items: center;
      background: rgba(255, 255, 255, .14);
      border-radius: 12px;
      color: #ffffff;
      display: inline-flex;
      height: 44px;
      justify-content: center;
      width: 44px;
    }

    .brand-title {
      display: block;
      font-size: 1.08rem;
      font-weight: 800;
      line-height: 1.05;
    }

    .brand-subtitle {
      color: var(--sidebar-muted);
      display: block;
      font-size: .68rem;
      font-weight: 700;
      letter-spacing: .04em;
      text-transform: uppercase;
    }

    body.sidebar-collapse .brand-link {
      gap: 0;
      justify-content: center;
      padding-left: .5rem;
      padding-right: .5rem;
    }

    body.sidebar-collapse .brand-title,
    body.sidebar-collapse .brand-subtitle,
    body.sidebar-collapse .user-panel .info,
    body.sidebar-collapse .nav-sidebar .nav-link p {
      display: none !important;
    }

    body.sidebar-collapse .brand-mark {
      height: 38px;
      width: 38px;
    }

    .sidebar {
      padding: 1.25rem .9rem;
    }

    body.sidebar-collapse .sidebar {
      padding-left: .45rem;
      padding-right: .45rem;
    }

    .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link,
    .sidebar-light-primary .nav-sidebar > .nav-item > .nav-link {
      align-items: center;
      border-radius: 12px;
      color: #d9e6ef;
      display: flex;
      font-size: .92rem;
      font-weight: 600;
      margin-bottom: .35rem;
      padding: .78rem .95rem;
      transition: background-color .15s ease, color .15s ease;
    }

    .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link:hover,
    .sidebar-light-primary .nav-sidebar > .nav-item > .nav-link:hover {
      background: var(--sidebar-hover);
      color: #ffffff !important;
    }

    .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active,
    .sidebar-light-primary .nav-sidebar > .nav-item > .nav-link.active {
      background: var(--sidebar-active) !important;
      box-shadow: 0 10px 18px rgba(0, 0, 0, .12);
      color: var(--sidebar-bg) !important;
    }

    .nav-sidebar .nav-icon {
      flex: 0 0 22px;
      margin-left: 0;
      margin-right: .7rem;
      text-align: center;
      width: 22px;
    }

    .nav-sidebar .nav-link p {
      line-height: 1.25;
      margin: 0;
    }

    .nav-sidebar .nav-link > .right {
      margin-left: auto;
      position: static;
      transform: none;
    }

    body.sidebar-collapse .nav-sidebar > .nav-item > .nav-link {
      align-items: center;
      display: flex;
      justify-content: center;
      padding-left: .65rem;
      padding-right: .65rem;
    }

    body.sidebar-collapse .nav-sidebar .nav-icon {
      margin-left: 0;
      margin-right: 0;
    }

    .nav-treeview .nav-link {
      align-items: center;
      border-radius: 10px;
      color: var(--sidebar-muted) !important;
      display: flex;
      font-size: .88rem;
      margin-left: 0;
      padding-left: 1rem;
    }

    body.sidebar-collapse .nav-treeview {
      display: none !important;
    }

    .nav-treeview .nav-link:hover {
      background: var(--sidebar-hover) !important;
      color: #ffffff !important;
    }

    .nav-treeview .nav-link.active {
      background: rgba(255, 255, 255, .16) !important;
      color: #ffffff !important;
    }

    .user-panel {
      border-bottom: 1px solid rgba(255, 255, 255, .12) !important;
    }

    .user-panel .info a {
      color: #ffffff !important;
      font-weight: 700;
    }

    body.sidebar-collapse .user-panel {
      justify-content: center;
      padding-left: 0;
    }

    body.sidebar-collapse .user-panel .image {
      padding-left: 0;
    }

    .navbar-search {
      max-width: 560px;
      position: relative;
      width: 100%;
    }

    .navbar-search .form-control {
      background: #f8fafc;
      border: 1px solid #dce3ec;
      border-radius: 14px;
      height: 48px;
      padding-left: 2.75rem;
    }

    .navbar-search .fa-search {
      color: #94a3b8;
      left: 1rem;
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      z-index: 2;
    }

    .topbar-avatar {
      align-items: center;
      background: var(--app-primary);
      border-radius: 999px;
      color: #ffffff;
      display: inline-flex;
      font-weight: 800;
      height: 42px;
      justify-content: center;
      width: 42px;
    }

    .account-trigger {
      align-items: center;
      border: 1px solid transparent;
      border-radius: 14px;
      color: #0f172a;
      display: flex;
      gap: .75rem;
      padding: .35rem .55rem .35rem .35rem;
      transition: background-color .15s ease, border-color .15s ease;
    }

    .account-trigger:hover {
      background: #f8fafc;
      border-color: var(--app-border);
      color: #0f172a;
      text-decoration: none;
    }

    .account-name {
      font-size: .95rem;
      font-weight: 800;
      line-height: 1.1;
    }

    .role-pill {
      background: #f0e7ff;
      border-radius: 999px;
      color: #7c3aed;
      display: inline-block;
      font-size: .72rem;
      font-weight: 700;
      padding: .2rem .7rem;
    }

    .card {
      border: 1px solid var(--app-border);
      border-radius: 8px;
      box-shadow: 0 8px 22px rgba(15, 23, 42, 0.04);
    }

    .card-header {
      border-bottom-color: var(--app-border);
      background: var(--app-surface);
    }

    .btn {
      border-radius: 6px;
      font-size: .9rem;
      font-weight: 600;
    }

    .btn-primary {
      background: var(--app-primary);
      border-color: var(--app-primary);
    }

    .btn-primary:hover,
    .btn-primary:focus {
      background: #0f466f;
      border-color: #0f466f;
    }

    .btn-sm {
      padding: .32rem .62rem;
    }

    .top-action-btn {
      align-items: center;
      display: inline-flex;
      font-size: .9rem;
      gap: .45rem;
      height: 38px;
      justify-content: center;
      padding: 0 .95rem;
    }

    .stat-card {
      min-height: 132px;
      overflow: hidden;
      position: relative;
    }

    .stat-card .stat-icon {
      align-items: center;
      border-radius: 8px;
      display: inline-flex;
      height: 42px;
      justify-content: center;
      width: 42px;
    }

    .stat-icon-primary {
      background: var(--app-primary);
      color: #ffffff;
    }

    .stat-label {
      color: var(--app-muted);
      font-size: .86rem;
      font-weight: 600;
      letter-spacing: .02em;
    }

    .stat-value {
      color: var(--app-ink);
      font-size: 1.85rem;
      font-weight: 800;
      line-height: 1.1;
    }

    .action-button {
      align-items: center;
      border: 1px solid var(--app-border);
      border-radius: 8px;
      color: var(--app-ink);
      display: flex;
      gap: .75rem;
      padding: .85rem 1rem;
      transition: background-color .15s ease, border-color .15s ease;
    }

    .action-button:hover {
      background: #f3f4f6;
      border-color: #cbd5e1;
      color: var(--app-ink);
      text-decoration: none;
    }

    .trend-bar {
      align-items: end;
      display: flex;
      gap: .75rem;
      margin-left: auto;
      margin-right: auto;
      max-width: 520px;
      min-height: 118px;
      padding-top: .5rem;
    }

    .trend-chart {
      display: flex;
      gap: .9rem;
      justify-content: center;
    }

    .trend-axis {
      color: var(--app-muted);
      display: flex;
      flex-direction: column;
      font-size: .72rem;
      justify-content: space-between;
      min-height: 118px;
      padding-top: .5rem;
      text-align: right;
      width: 24px;
    }

    .trend-item {
      flex: 1;
      text-align: center;
    }

    .trend-fill {
      background: var(--app-primary);
      border-radius: 6px 6px 0 0;
      min-height: 8px;
      width: 100%;
    }

    .trend-fill-muted {
      background: #d8e2ec;
    }

    .trend-summary {
      color: var(--app-muted);
      font-size: .86rem;
      font-weight: 500;
    }

    .trend-empty {
      align-items: center;
      color: var(--app-muted);
      display: flex;
      justify-content: center;
      min-height: 118px;
      text-align: center;
    }

    .line-chart-wrap {
      margin-left: auto;
      margin-right: auto;
      max-width: 620px;
    }

    .line-chart {
      height: 168px;
      overflow: visible;
      width: 100%;
    }

    .chart-grid {
      stroke: #e8eef5;
      stroke-dasharray: 4 6;
      stroke-width: 1;
    }

    .chart-area {
      fill: rgba(21, 85, 138, .08);
    }

    .chart-line {
      fill: none;
      stroke: var(--app-primary);
      stroke-linecap: round;
      stroke-linejoin: round;
      stroke-width: 3;
    }

    .chart-point {
      fill: #ffffff;
      stroke: var(--app-primary);
      stroke-width: 3;
    }

    .chart-point-muted {
      fill: #d8e2ec;
      stroke: #d8e2ec;
    }

    .chart-labels {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      margin-top: .35rem;
      text-align: center;
    }

    .chart-labels span {
      color: var(--app-muted);
      font-size: .75rem;
    }

    .trend-label {
      color: var(--app-muted);
      display: block;
      font-size: .75rem;
      margin-top: .35rem;
    }

    .app-table {
      border-collapse: separate;
      border-spacing: 0;
      color: #334155;
      font-size: .9rem;
    }

    .app-table thead th {
      background: #f8fafc;
      border-bottom: 1px solid var(--app-border);
      color: #64748b;
      font-size: .72rem;
      font-weight: 800;
      letter-spacing: .03em;
      padding: .85rem 1rem;
      text-transform: uppercase;
      white-space: nowrap;
    }

    .app-table tbody td {
      border-top: 1px solid #eef2f7;
      padding: .9rem 1rem;
      vertical-align: middle;
    }

    .app-table tbody tr:hover {
      background: #f8fafc;
    }

    .table-title {
      font-weight: 800;
      margin-bottom: .15rem;
    }

    .table-subtitle {
      color: var(--app-muted);
      margin-bottom: 0;
    }

    .action-cell {
      text-align: right;
      white-space: nowrap;
      width: 1%;
    }

    .record-day-row td {
      background: #f8fafc;
      color: #475569;
      font-size: .8rem;
      font-weight: 800;
      letter-spacing: .02em;
    }

    .id-badge {
      background: #eef6ff;
      border: 1px solid #d8e9fa;
      border-radius: 999px;
      color: var(--app-primary);
      display: inline-block;
      font-weight: 700;
      padding: .25rem .55rem;
    }

    .info-list-item {
      align-items: flex-start;
      display: flex;
      gap: .85rem;
      padding: .75rem 0;
    }

    .info-list-item + .info-list-item {
      border-top: 1px solid var(--app-border);
    }

    .info-icon {
      align-items: center;
      border-radius: 10px;
      display: inline-flex;
      flex: 0 0 38px;
      height: 38px;
      justify-content: center;
      width: 38px;
    }

    .info-icon-primary {
      background: #e8f2fb;
      color: var(--app-primary);
    }

    .info-icon-success {
      background: #e8f7ef;
      color: #198754;
    }

    .info-icon-info {
      background: #e8f7fb;
      color: #0f7792;
    }

    .app-delete-toast {
      background: #ecfdf3 !important;
      border: 1px solid #a7f3c5 !important;
      border-radius: 8px !important;
      box-shadow: 0 14px 32px rgba(15, 23, 42, .12) !important;
      color: #067a3f !important;
      padding: 1.05rem 1.25rem !important;
      width: 445px !important;
    }

    .app-delete-toast-title {
      color: #067a3f !important;
      font-size: 1rem !important;
      font-weight: 700 !important;
      line-height: 1.3 !important;
      margin: 0 !important;
      padding-left: .35rem !important;
      text-align: left !important;
    }

    .app-delete-toast .swal2-icon {
      border: 0 !important;
      height: 1.35rem !important;
      margin: 0 .65rem 0 0 !important;
      width: 1.35rem !important;
    }

    .app-delete-toast .swal2-success-ring {
      display: none !important;
    }

    .app-delete-toast .swal2-success-line-tip,
    .app-delete-toast .swal2-success-line-long {
      background-color: #067a3f !important;
    }

    .app-delete-toast-close {
      align-items: center !important;
      background: #ecfdf3 !important;
      border: 1px solid #86efac !important;
      border-radius: 999px !important;
      color: #16a34a !important;
      display: inline-flex !important;
      font-size: 1rem !important;
      height: 24px !important;
      justify-content: center !important;
      left: -10px !important;
      line-height: 1 !important;
      padding: 0 !important;
      position: absolute !important;
      top: -10px !important;
      width: 24px !important;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <?php if (basename($_SERVER['PHP_SELF']) !== 'index.php'): ?>
        <li class="nav-item d-none d-md-inline-block ml-2">
          <div class="navbar-search">
            <i class="fas fa-search"></i>
            <input class="form-control js-table-search" type="search" placeholder="Search records, users, card IDs..." aria-label="Search">
          </div>
        </li>
      <?php endif; ?>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item d-none d-sm-inline-flex align-items-center">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item d-none d-sm-inline-flex align-items-center">
        <div class="dropdown ml-2">
          <a class="account-trigger" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="topbar-avatar"><?php echo e(strtoupper(substr($_SESSION['username'] ?? 'A', 0, 2))); ?></span>
            <span class="text-left">
              <span class="account-name d-block"><?php echo e($_SESSION['username'] ?? 'Admin'); ?></span>
              <span class="role-pill">Admin</span>
            </span>
            <i class="fas fa-chevron-down text-muted ml-1"></i>
          </a>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="profile.php">Profile</a>
            <a class="dropdown-item" href="logout.php">Logout</a>
          </div>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
