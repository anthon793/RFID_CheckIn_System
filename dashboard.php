<?php
$current_page = basename($_SERVER['PHP_SELF']);
$records_open = $current_page === 'records.php';
$users_open = in_array($current_page, ['users.php', 'adduser.php'], true);
?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <span class="brand-mark"><i class="fas fa-fingerprint"></i></span>
      <span>
        <span class="brand-title">RFID Check-In</span>
        <span class="brand-subtitle">Access Platform</span>
      </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel pb-3 mb-3 d-flex align-items-center">
        <div class="image">
          <img src="images/beard.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo e($_SESSION['username'] ?? 'Admin'); ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="index.php" class="nav-link <?php echo $current_page === 'index.php' ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item <?php echo $records_open ? 'menu-open' : ''; ?>">
            <a href="#" class="nav-link <?php echo $records_open ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-clipboard-list"></i>
              <p>Records</p>
              <i class="right fas fa-angle-left"></i>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="records.php" class="nav-link <?php echo $current_page === 'records.php' ? 'active' : ''; ?>">
                  <i class="fas fa-tasks nav-icon"></i>
                  <p>View Records</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item <?php echo $users_open ? 'menu-open' : ''; ?>">
            <a href="#" class="nav-link <?php echo $users_open ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-user-shield"></i>
              <p>Users</p>
              <i class="right fas fa-angle-left"></i>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="users.php" class="nav-link <?php echo $current_page === 'users.php' ? 'active' : ''; ?>">
                  <i class="fas fa-address-book nav-icon"></i>
                  <p>View All Users</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="adduser.php" class="nav-link <?php echo $current_page === 'adduser.php' ? 'active' : ''; ?>">
                  <i class="fas fa-user-plus nav-icon"></i>
                  <p>Add New User</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="profile.php" class="nav-link <?php echo $current_page === 'profile.php' ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-user-circle"></i>
              <p>Profile</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>



  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

