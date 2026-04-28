<?php
include "connection.php";
include "app_helpers.php";

require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    if (!valid_csrf_token($_POST['csrf_token'] ?? '')) {
        set_flash('error', 'Request blocked', 'Please refresh the page and try again.');
        header('Location: adduser.php');
        exit();
    }

    require "gump.class.php";

    $gump = new GUMP();
    $_POST = $gump->sanitize($_POST);

    $gump->validation_rules([
        'username' => 'required|alpha_numeric|max_len,20|min_len,4',
        'firstname' => 'required|alpha|max_len,30|min_len,2',
        'lastname' => 'required|alpha|max_len,30|min_len,1',
        'email' => 'required|valid_email',
        'password' => 'required|max_len,50|min_len,6',
    ]);

    $gump->filter_rules([
        'username' => 'trim|sanitize_string',
        'firstname' => 'trim|sanitize_string',
        'lastname' => 'trim|sanitize_string',
        'password' => 'trim',
        'email' => 'trim|sanitize_email',
    ]);

    $validated_data = $gump->run($_POST);

    if ($validated_data === false) {
        set_flash('error', 'Check user details', strip_tags($gump->get_readable_errors(true)));
    } else if ($_POST['password'] !== $_POST['cpassword']) {
        set_flash('error', 'Password mismatch', 'Password and confirmation must match.');
    } else {
        $username = $validated_data['username'];
        $firstname = $validated_data['firstname'];
        $lastname = $validated_data['lastname'];
        $email = $validated_data['email'];
        $pass = $validated_data['password'];
        $password = password_hash($pass, PASSWORD_DEFAULT);

        $check_user = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
        $check_user->bind_param("ss", $username, $email);
        $check_user->execute();
        $existing_user = $check_user->get_result();

        if ($existing_user->num_rows > 0) {
            set_flash('warning', 'User already exists', 'Use a different username or email address.');
        } else {
            $statement = $conn->prepare("INSERT INTO users(username, firstname, lastname, email, password) VALUES (?, ?, ?, ?, ?)");
            $statement->bind_param("sssss", $username, $firstname, $lastname, $email, $password);
            $statement->execute();

            if ($statement->affected_rows > 0) {
                set_flash('success', 'User added', 'The new user has been successfully added.');
            } else {
                set_flash('error', 'Could not add user', 'An error occurred. Please try again.');
            }

            $statement->close();
        }

        $check_user->close();
    }

    header('Location: adduser.php');
    exit();
}

$flash = get_flash();

include "header.php";
include "dashboard.php";
?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2 align-items-center">
        <div class="col-sm-6">
          <h1 class="m-0">Add User</h1>
          <p class="text-muted mb-0">Create a dashboard account for an administrator.</p>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="users.php">Users</a></li>
            <li class="breadcrumb-item active">Add User</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-8">
          <form method="POST">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">User Information</h3>
              </div>
              <div class="card-body">
                <?php echo csrf_field(); ?>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="username">Username</label>
                      <input type="text" name="username" id="username" class="form-control" placeholder="e.g. admin01" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" name="email" id="email" class="form-control" placeholder="name@example.com" required>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="firstname">Firstname</label>
                      <input type="text" name="firstname" id="firstname" class="form-control" placeholder="Firstname" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="lastname">Lastname</label>
                      <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Lastname" required>
                    </div>
                  </div>
                </div>

                <hr>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group mb-md-0">
                      <label for="password">Password</label>
                      <input type="password" name="password" id="password" class="form-control" placeholder="Minimum 6 characters" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group mb-0">
                      <label for="cpassword">Confirm Password</label>
                      <input type="password" name="cpassword" id="cpassword" class="form-control" placeholder="Repeat password" required>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer d-flex justify-content-between">
                <a href="users.php" class="btn btn-outline-secondary">
                  <i class="fas fa-arrow-left"></i> Back to Users
                </a>
                <button type="submit" name="add" class="btn btn-primary">
                  <i class="fas fa-user-plus"></i> Create User
                </button>
              </div>
            </div>
          </form>
        </div>

        <div class="col-lg-4">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Account Setup</h3>
            </div>
            <div class="card-body">
              <div class="info-list-item pt-0">
                <div class="info-icon info-icon-primary">
                  <i class="fas fa-user-shield"></i>
                </div>
                <div>
                  <h6 class="mb-1">Dashboard Access</h6>
                  <p class="text-muted mb-0">Users created here can sign in and manage records.</p>
                </div>
              </div>
              <div class="info-list-item">
                <div class="info-icon info-icon-success">
                  <i class="fas fa-key"></i>
                </div>
                <div>
                  <h6 class="mb-1">Secure Passwords</h6>
                  <p class="text-muted mb-0">Passwords are hashed before they are stored.</p>
                </div>
              </div>
              <div class="info-list-item pb-0">
                <div class="info-icon info-icon-info">
                  <i class="fas fa-envelope"></i>
                </div>
                <div>
                  <h6 class="mb-1">Unique Identity</h6>
                  <p class="text-muted mb-0">Use a unique username and email for each account.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php if ($flash): ?>
  <script>
    window.appFlash = <?php echo json_encode($flash); ?>;
  </script>
<?php endif; ?>

<footer class="main-footer">
  <div class="float-right d-none d-sm-block"></div>
  <strong>Copyright &copy;Dashboard</strong> All rights reserved.
</footer>

<aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<?php
include('footer.php');
?>
