  <?php
  session_start();

  if (isset($_SESSION['user_id'])) {
      switch ($_SESSION['role']) {
          case 'sekretaris': header("Location: dashboard/sekretaris/index.php"); break;
          case 'ketua':      header("Location: dashboard/ketua/index.php");      break;
          case 'anggota':    header("Location: dashboard/anggota/index.php");    break;
      }
      exit;
  }

  $error = $_SESSION['login_error'] ?? '';
  unset($_SESSION['login_error']);

  $page_title = 'Login - Senandika';
  $asset_path = './';
  include 'includes/head.php';
  ?>

  <style>
  .login-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--body-bg);
    padding: 24px;
  }

  /* Tombol Back */
  .btn-back {
    position: fixed;
    top: 24px;
    left: 28px;
    display: flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    color: var(--primary);
    font-family: var(--font-head);
    font-weight: 600;
    font-size: 0.88rem;
    padding: 9px 18px;
    border-radius: 50px;
    text-decoration: none;
    border: 1.5px solid var(--border);
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    transition: all 0.18s ease;
    z-index: 100;
  }
  .btn-back:hover {
    background: #FFF0F3;
    border-color: var(--primary);
    color: var(--primary);
    transform: translateX(-2px);
  }

  .login-card {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow-md);
    padding: 48px 44px;
    width: 100%;
    max-width: 420px;
  }

  .login-logo {
    display: block;
    width: 72px;
    height: 72px;
    margin: 0 auto 20px;
    border-radius: 50%;
    object-fit: cover;
  }

  .login-title {
    font-family: var(--font-head);
    font-size: 1.5rem;
    font-weight: 800;
    text-align: center;
    color: var(--primary);
    margin-bottom: 6px;
  }

  .login-sub {
    text-align: center;
    font-size: 0.88rem;
    color: var(--text-muted);
    margin-bottom: 28px;
  }

  .password-wrapper { position: relative; }
  .password-wrapper input { padding-right: 44px; }
  .toggle-password {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: var(--text-muted);
    font-size: 1rem;
    padding: 0;
    display: flex;
    align-items: center;
    transition: color 0.15s;
  }
  .toggle-password:hover { color: var(--primary); }

  .btn-login {
    background: var(--primary);
    color: #fff;
    font-family: var(--font-head);
    font-weight: 700;
    font-size: 0.95rem;
    padding: 12px;
    border-radius: 50px;
    border: none;
    cursor: pointer;
    width: 100%;
    transition: background 0.18s;
  }
  .btn-login:hover { background: var(--primary-dark); }
  </style>

  <!-- Tombol Back ke Landing Page -->
  <a href="index.php" class="btn-back">
    <i class="bi bi-arrow-left"></i> Kembali
  </a>

  <div class="login-wrapper">
    <div class="login-card">
      <img src="assets/img/logo.jpg" alt="Logo" class="login-logo"
          onerror="this.style.display='none'">
      <div class="login-title">Senandika</div>
      <div class="login-sub">Sistem Arsip Dokumen PARASIKA</div>

      <?php if (!empty($error)): ?>
      <div class="alert alert-danger rounded-3 py-2 px-3 mb-3" style="font-size:0.87rem;">
        <i class="bi bi-exclamation-circle me-1"></i>
        <?= htmlspecialchars($error) ?>
      </div>
      <?php endif; ?>

      <form action="proses-login.php" method="POST">
        <div class="mb-3">
          <label class="form-label fw-semibold" style="font-family:var(--font-head); font-size:.88rem;">
            NPM
          </label>
          <input type="text" name="npm" class="form-control rounded-pill"
                placeholder="Masukkan NPM"
                value="<?= htmlspecialchars($_POST['npm'] ?? '') ?>"
                required autofocus>
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold" style="font-family:var(--font-head); font-size:.88rem;">
            Password
          </label>
          <div class="password-wrapper">
            <input type="password" name="password" id="passwordInput"
                  class="form-control rounded-pill"
                  placeholder="Masukkan password" required>
            <button type="button" class="toggle-password" id="togglePassword">
              <i class="bi bi-eye" id="eyeIcon"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="btn-login">Masuk</button>
      </form>
    </div>
  </div>

  <script>
  document.getElementById('togglePassword').addEventListener('click', function () {
    const input   = document.getElementById('passwordInput');
    const eyeIcon = document.getElementById('eyeIcon');
    if (input.type === 'password') {
      input.type = 'text';
      eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
      input.type = 'password';
      eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
    }
  });
  </script>

  <?php include 'includes/scripts.php'; ?>