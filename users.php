<?php 
include ('connection.php');
include ('app_helpers.php');

require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
  if (!valid_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash('error', 'Update blocked', 'Please refresh the page and try again.');
    header('Location: users.php');
    exit();
  }

  $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
  $username = trim($_POST['username'] ?? '');
  $firstname = trim($_POST['firstname'] ?? '');
  $lastname = trim($_POST['lastname'] ?? '');
  $email = trim($_POST['email'] ?? '');

  if (!$user_id || $username === '' || $firstname === '' || $lastname === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_flash('error', 'Invalid details', 'Please provide a valid username, name, and email address.');
    header('Location: users.php');
    exit();
  }

  $check_user = $conn->prepare('SELECT id FROM users WHERE username = ? AND id <> ? LIMIT 1');
  $check_user->bind_param('si', $username, $user_id);
  $check_user->execute();
  $duplicate_user = $check_user->get_result();

  if ($duplicate_user->num_rows > 0) {
    set_flash('warning', 'Username taken', 'Please choose a different username.');
    $check_user->close();
    header('Location: users.php');
    exit();
  }

  $check_user->close();

  $statement = $conn->prepare('UPDATE users SET username = ?, firstname = ?, lastname = ?, email = ? WHERE id = ?');
  $statement->bind_param('ssssi', $username, $firstname, $lastname, $email, $user_id);
  $statement->execute();

  if ($statement->affected_rows >= 0) {
    if ($user_id === (int) $_SESSION['id']) {
      $_SESSION['username'] = $username;
    }

    set_flash('success', 'User updated', 'The user details were saved successfully.');
  } else {
    set_flash('error', 'Update failed', 'The user could not be updated.');
  }

  $statement->close();
  header('Location: users.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
  if (!valid_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash('error', 'Delete blocked', 'Please refresh the page and try again.');
    header('Location: users.php');
    exit();
  }

  $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);

  if ($user_id && $user_id !== (int) $_SESSION['id']) {
    $statement = $conn->prepare('DELETE FROM users WHERE id = ?');
    $statement->bind_param('i', $user_id);
    $statement->execute();

    if ($statement->affected_rows > 0) {
      set_flash('success', 'User deleted successfully', '', 'delete_success');
    } else {
      set_flash('warning', 'User not found', 'That user may have already been deleted.');
    }

    $statement->close();
  } else if ($user_id === (int) $_SESSION['id']) {
    set_flash('warning', 'Action blocked', 'You cannot delete the account you are currently using.');
  } else {
    set_flash('error', 'Invalid user', 'The selected user could not be deleted.');
  }

  header('Location: users.php');
  exit();
}

$flash = get_flash();
$edit_modals = '';

include "header.php";
$count = 0;
?> 

<?php include "dashboard.php"; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 font-weight-bold">User Management</h1>
          <p class="text-muted mb-0">Manage dashboard administrators and account details.</p>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">DataTables</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <div class="d-flex align-items-center justify-content-between w-100">
              <div>
                <h3 class="table-title">Users Table</h3>
                <p class="table-subtitle">Administrator accounts with edit and delete controls</p>
              </div>
              <a href="adduser.php" class="btn btn-primary btn-sm">
                <i class="fas fa-user-plus"></i> Add User
              </a>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="table-responsive">
              <table id="example2" class="table app-table mb-0">
                <thead>
                  <tr>
                    <th>S/N</th>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th class="action-cell">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $query = "SELECT * FROM users";
                    $select_users = mysqli_query($conn, $query) or die(mysqli_error($conn));
                    if (mysqli_num_rows($select_users) > 0) {
                      while ($row = mysqli_fetch_array($select_users)) {
                        $count++;
                        $user_id = $row['id'];
                        $username = $row['username'];
                        $user_firstname = $row['firstname'];
                        $user_lastname = $row['lastname'];
                        $user_email = $row['email'];
                        $edit_modal_id = 'editUserModal' . $user_id;
                        echo "<tr>";
                        echo "<td>" . e($count) . "</td>";
                        echo "<td><span class='id-badge'>" . e($username) . "</span></td>";
                        echo "<td><strong>" . e($user_firstname . ' ' . $user_lastname) . "</strong></td>";
                        echo "<td>" . e($user_email) . "</td>";
                        echo "<td class='action-cell'>
                          <button type='button' class='btn btn-outline-primary btn-sm mr-1' data-toggle='modal' data-target='#" . e($edit_modal_id) . "'>
                            <i class='fas fa-edit'></i> Edit
                          </button>
                          <form method='POST' class='js-delete-form d-inline' data-title='Delete user?' data-text='This user account will be permanently removed.'>
                            " . csrf_field() . "
                            <input type='hidden' name='delete_user' value='1'>
                            <input type='hidden' name='user_id' value='" . e($user_id) . "'>
                            <button type='submit' class='btn btn-outline-danger btn-sm'>
                              <i class='fas fa-trash-alt'></i> Delete
                            </button>
                          </form>
                        </td>";
                        echo "</tr>";
                        $edit_modals .= "
                        <div class='modal fade' id='" . e($edit_modal_id) . "' tabindex='-1' role='dialog' aria-labelledby='" . e($edit_modal_id) . "Label' aria-hidden='true'>
                          <div class='modal-dialog' role='document'>
                            <div class='modal-content'>
                              <form method='POST'>
                                <div class='modal-header'>
                                  <h5 class='modal-title' id='" . e($edit_modal_id) . "Label'>Edit User</h5>
                                  <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                  </button>
                                </div>
                                <div class='modal-body'>
                                  " . csrf_field() . "
                                  <input type='hidden' name='user_id' value='" . e($user_id) . "'>
                                  <div class='form-group'>
                                    <label>Username</label>
                                    <input type='text' name='username' class='form-control' value='" . e($username) . "' required>
                                  </div>
                                  <div class='form-group'>
                                    <label>Firstname</label>
                                    <input type='text' name='firstname' class='form-control' value='" . e($user_firstname) . "' required>
                                  </div>
                                  <div class='form-group'>
                                    <label>Lastname</label>
                                    <input type='text' name='lastname' class='form-control' value='" . e($user_lastname) . "' required>
                                  </div>
                                  <div class='form-group mb-0'>
                                    <label>Email</label>
                                    <input type='email' name='email' class='form-control' value='" . e($user_email) . "' required>
                                  </div>
                                </div>
                                <div class='modal-footer'>
                                  <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
                                  <button type='submit' name='update_user' class='btn btn-primary'>
                                    <i class='fas fa-save'></i> Save changes
                                  </button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>";
                      }
                    } else {
                      echo "<tr><td colspan='5' class='text-center text-muted'>No users found.</td></tr>";
                    }
                  ?>
                </tbody>
              </table>
              </div>
              <?php echo $edit_modals; ?>
             
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
     <!-- Main row -->
     <div class="row">
          
          </div>
          <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <?php if ($flash): ?>
      <script>
        window.appFlash = <?php echo json_encode($flash); ?>;
      </script>
    <?php endif; ?>
  
    <!-- /.content-wrapper -->
    <footer class="main-footer">
      <strong>Copyright &copy;</strong>
      All rights reserved.
    </footer>
  
    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  
    <?php
    
  include("footer.php");
  
  ?>
