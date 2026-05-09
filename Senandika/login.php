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

  <link rel="stylesheet" href="assets/css/auth.css">

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
        <div class="text-center mt-4">
          <span style="font-size: 0.88rem; color: var(--text-muted);">Belum punya akun?</span>
          <a href="registrasi.php" style="font-size: 0.88rem; font-weight: 700; color: var(--primary); text-decoration: none; font-family: var(--font-head);">Daftar di sini</a>
        </div>
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