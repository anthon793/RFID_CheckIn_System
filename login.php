<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | RFID Check-In</title>
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='16' fill='%2315558a'/%3E%3Cpath d='M32 14c-9.4 0-17 7.6-17 17v6' fill='none' stroke='white' stroke-width='4' stroke-linecap='round'/%3E%3Cpath d='M23 47V31c0-5 4-9 9-9s9 4 9 9v3' fill='none' stroke='white' stroke-width='4' stroke-linecap='round'/%3E%3Cpath d='M32 30v20' fill='none' stroke='white' stroke-width='4' stroke-linecap='round'/%3E%3Cpath d='M41 43c4-3 6-7 6-12 0-8.3-6.7-15-15-15' fill='none' stroke='white' stroke-width='4' stroke-linecap='round' opacity='.82'/%3E%3C/svg%3E">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <style>
    :root {
      --auth-primary: #15558a;
      --auth-primary-dark: #103c5b;
      --auth-muted: #64748b;
      --auth-border: #dce3ec;
      --auth-soft: #f8fafc;
    }

    body {
      background: var(--auth-soft);
      color: #0f172a;
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      min-height: 100vh;
    }

    .auth-shell {
      align-items: stretch;
      display: flex;
      min-height: 100vh;
    }

    .auth-panel {
      background: var(--auth-primary-dark);
      color: #ffffff;
      display: flex;
      flex: 0 0 42%;
      flex-direction: column;
      justify-content: space-between;
      padding: 3rem;
    }

    .auth-brand {
      align-items: center;
      display: flex;
      gap: .85rem;
    }

    .auth-brand-icon {
      align-items: center;
      background: rgba(255, 255, 255, .14);
      border-radius: 14px;
      display: inline-flex;
      height: 52px;
      justify-content: center;
      width: 52px;
    }

    .auth-brand-title {
      display: block;
      font-size: 1.35rem;
      font-weight: 800;
      line-height: 1.1;
    }

    .auth-brand-subtitle {
      color: #a9c2d4;
      display: block;
      font-size: .78rem;
      font-weight: 700;
      letter-spacing: .04em;
      text-transform: uppercase;
    }

    .auth-copy h1 {
      font-size: 2.15rem;
      font-weight: 800;
      line-height: 1.15;
      margin-bottom: 1rem;
    }

    .auth-copy p {
      color: #c8d7e4;
      font-size: 1rem;
      line-height: 1.7;
      max-width: 440px;
    }

    .auth-meta {
      color: #a9c2d4;
      font-size: .9rem;
    }

    .auth-form-wrap {
      align-items: center;
      display: flex;
      flex: 1;
      justify-content: center;
      padding: 2rem;
    }

    .auth-card {
      background: #ffffff;
      border: 1px solid var(--auth-border);
      border-radius: 14px;
      box-shadow: 0 18px 45px rgba(15, 23, 42, .08);
      max-width: 430px;
      padding: 2rem;
      width: 100%;
    }

    .auth-card h2 {
      font-size: 1.65rem;
      font-weight: 800;
      margin-bottom: .35rem;
    }

    .auth-card .text-muted {
      color: var(--auth-muted) !important;
    }

    .auth-input {
      position: relative;
    }

    .auth-input .form-control {
      border: 1px solid var(--auth-border);
      border-radius: 10px;
      height: 48px;
      padding-left: 2.75rem;
    }

    .auth-input .form-control:focus {
      border-color: var(--auth-primary);
      box-shadow: 0 0 0 .2rem rgba(21, 85, 138, .12);
    }

    .auth-input i {
      color: #94a3b8;
      left: 1rem;
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      z-index: 2;
    }

    .password-toggle {
      align-items: center;
      background: transparent;
      border: 0;
      color: #64748b;
      display: inline-flex;
      height: 48px;
      justify-content: center;
      padding: 0;
      position: absolute;
      right: .9rem;
      top: 0;
      width: 32px;
      z-index: 3;
    }

    .password-toggle:focus {
      outline: none;
    }

    .auth-input.has-toggle .form-control {
      padding-right: 3rem;
    }

    .btn-auth {
      align-items: center;
      background: var(--auth-primary);
      border-color: var(--auth-primary);
      border-radius: 10px;
      display: inline-flex;
      font-weight: 700;
      height: 48px;
      justify-content: center;
    }

    .btn-auth:hover,
    .btn-auth:focus {
      background: #0f466f;
      border-color: #0f466f;
    }

    @media (max-width: 991.98px) {
      .auth-shell {
        display: block;
      }

      .auth-panel {
        min-height: 280px;
        padding: 2rem;
      }

      .auth-form-wrap {
        padding: 1.25rem;
      }
    }
  </style>
</head>
<body>
<main class="auth-shell">
  <section class="auth-panel">
    <div class="auth-brand">
      <span class="auth-brand-icon"><i class="fas fa-fingerprint"></i></span>
      <span>
        <span class="auth-brand-title">RFID Check-In</span>
        <span class="auth-brand-subtitle">Access Platform</span>
      </span>
    </div>

    <div class="auth-copy">
      <h1>Monitor access activity with confidence.</h1>
      <p>Sign in to review RFID check-ins, manage dashboard users, and keep attendance records organized in real time.</p>
    </div>

    <div class="auth-meta">
      <i class="fas fa-shield-alt mr-2"></i> Secure administrator access
    </div>
  </section>

  <section class="auth-form-wrap">
    <div class="auth-card">
      <h2>Welcome back</h2>
      <p class="text-muted mb-4">Enter your credentials to continue.</p>

      <form action="login_process.php" method="post">
        <div class="form-group">
          <label for="email">Email address</label>
          <div class="auth-input">
            <i class="fas fa-envelope"></i>
            <input type="email" class="form-control" id="email" placeholder="admin@example.com" name="email" required>
          </div>
        </div>

        <div class="form-group mb-4">
          <label for="user_password">Password</label>
          <div class="auth-input has-toggle">
            <i class="fas fa-lock"></i>
            <input type="password" class="form-control" id="user_password" placeholder="Enter password" name="user_password" required>
            <button type="button" class="password-toggle" id="togglePassword" aria-label="Show password">
              <span class="fas fa-eye"></span>
            </button>
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block btn-auth" name="login">
          <i class="fas fa-arrow-right mr-2"></i> Sign In
        </button>
      </form>
    </div>
  </section>
</main>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script>
  document.getElementById('togglePassword').addEventListener('click', function () {
    var passwordInput = document.getElementById('user_password');
    var icon = this.querySelector('span');
    var isPassword = passwordInput.type === 'password';

    passwordInput.type = isPassword ? 'text' : 'password';
    icon.classList.toggle('fa-eye', !isPassword);
    icon.classList.toggle('fa-eye-slash', isPassword);
    this.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
  });
</script>
</body>
</html>
