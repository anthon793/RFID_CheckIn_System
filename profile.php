<?php
include "connection.php";
include "app_helpers.php";

require_login();

$userid = (int) $_SESSION['id'];

function load_profile($conn, $userid)
{
    $statement = $conn->prepare("SELECT id, username, password, email, firstname, lastname FROM users WHERE id = ? LIMIT 1");
    $statement->bind_param("i", $userid);
    $statement->execute();
    $result = $statement->get_result();
    $profile = $result->fetch_assoc();
    $statement->close();

    return $profile;
}

$profile = load_profile($conn, $userid);

if (!$profile) {
    session_destroy();
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    if (!valid_csrf_token($_POST['csrf_token'] ?? '')) {
        set_flash('error', 'Update blocked', 'Please refresh the page and try again.');
        header('Location: profile.php');
        exit();
    }

    require "gump.class.php";

    $gump = new GUMP();
    $_POST = $gump->sanitize($_POST);

    $gump->validation_rules([
        'firstname' => 'required|alpha|max_len,30|min_len,2',
        'lastname' => 'required|alpha|max_len,30|min_len,1',
        'email' => 'required|valid_email',
        'currentpassword' => 'required|max_len,50|min_len,6',
    ]);

    $gump->filter_rules([
        'firstname' => 'trim|sanitize_string',
        'lastname' => 'trim|sanitize_string',
        'currentpassword' => 'trim',
        'newpassword' => 'trim',
        'confirmnewpassword' => 'trim',
        'email' => 'trim|sanitize_email',
    ]);

    $validated_data = $gump->run($_POST);
    $new_password = trim($_POST['newpassword'] ?? '');
    $confirm_new_password = trim($_POST['confirmnewpassword'] ?? '');

    if ($validated_data === false) {
        set_flash('error', 'Check your details', strip_tags($gump->get_readable_errors(true)));
    } else if (!password_verify($validated_data['currentpassword'], $profile['password'])) {
        set_flash('error', 'Wrong password', 'Your current password is incorrect.');
    } else if ($new_password !== '' && strlen($new_password) < 6) {
        set_flash('error', 'Password too short', 'Your new password must be at least 6 characters.');
    } else if ($new_password !== $confirm_new_password) {
        set_flash('error', 'Password mismatch', 'The new password and confirmation do not match.');
    } else {
        $firstname = $validated_data['firstname'];
        $lastname = $validated_data['lastname'];
        $email = $validated_data['email'];

        if ($new_password === '') {
            $statement = $conn->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ? WHERE id = ?");
            $statement->bind_param("sssi", $firstname, $lastname, $email, $userid);
        } else {
            $password = password_hash($new_password, PASSWORD_DEFAULT);
            $statement = $conn->prepare("UPDATE users SET password = ?, firstname = ?, lastname = ?, email = ? WHERE id = ?");
            $statement->bind_param("ssssi", $password, $firstname, $lastname, $email, $userid);
        }

        $statement->execute();
        $statement->close();

        $_SESSION['username'] = $profile['username'];
        set_flash('success', 'Profile updated', 'Your account details were saved successfully.');
    }

    header('Location: profile.php');
    exit();
}

$flash = get_flash();
$username = $profile['username'];
$useremail = $profile['email'];
$userfirstname = $profile['firstname'];
$userlastname = $profile['lastname'];
$initials = strtoupper(substr($userfirstname, 0, 1) . substr($userlastname, 0, 1));

include "header.php";
include "dashboard.php";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2 align-items-center">
        <div class="col-sm-6">
          <h1 class="m-0">Profile</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Profile</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-4">
          <div class="card card-primary card-outline">
            <div class="card-body box-profile">
              <div class="text-center">
                <div class="profile-user-img img-fluid img-circle bg-primary d-inline-flex align-items-center justify-content-center" style="height: 100px; width: 100px; border: 3px solid #adb5bd;">
                  <span class="h2 mb-0 text-white"><?php echo e($initials); ?></span>
                </div>
              </div>

              <h3 class="profile-username text-center mt-3 mb-0"><?php echo e($userfirstname . ' ' . $userlastname); ?></h3>
              <p class="text-muted text-center mb-4"><?php echo e($username); ?></p>

              <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                  <b>Email</b>
                  <span class="float-right text-muted"><?php echo e($useremail); ?></span>
                </li>
                <li class="list-group-item">
                  <b>Account ID</b>
                  <span class="float-right text-muted">#<?php echo e($userid); ?></span>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <div class="col-lg-8">
          <form method="POST">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Account Details</h3>
              </div>
              <div class="card-body">
                <?php echo csrf_field(); ?>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="username">Username</label>
                      <input type="text" name="username" id="username" class="form-control" value="<?php echo e($username); ?>" readonly>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" name="email" id="email" class="form-control" value="<?php echo e($useremail); ?>" required>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="firstname">Firstname</label>
                      <input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo e($userfirstname); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="lastname">Lastname</label>
                      <input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo e($userlastname); ?>" required>
                    </div>
                  </div>
                </div>

                <hr>

                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group mb-md-0">
                      <label for="currentpassword">Current Password</label>
                      <input type="password" name="currentpassword" id="currentpassword" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group mb-md-0">
                      <label for="newpassword">New Password</label>
                      <input type="password" name="newpassword" id="newpassword" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group mb-0">
                      <label for="confirmnewpassword">Confirm Password</label>
                      <input type="password" name="confirmnewpassword" id="confirmnewpassword" class="form-control">
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer text-right">
                <button type="submit" name="update" class="btn btn-primary">
                  <i class="fas fa-save"></i> Save Changes
                </button>
              </div>
            </div>
          </form>
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
  <strong>Copyright &copy;<a href="https://adminlte.io">Dashboard</a>.</strong> All rights reserved.
</footer>

<aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<?php
include('footer.php');
?>
