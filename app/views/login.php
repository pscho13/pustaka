<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>UKK PERPUS 2024 | Log in</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= urlTo('/public/adminlte/plugins/fontawesome-free/css/all.min.css'); ?>">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?= urlTo('/public/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css'); ?>">
  <!-- sweetalert2 -->
  <link rel="stylesheet" type="text/css" href="<?= urlTo('/public/adminlte/plugins/sweetalert2/sweetalert2.css'); ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= urlTo('/public/adminlte/css/adminlte.min.css'); ?>">
  <!-- Google reCAPTCHA -->
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Selamat Datang Kembali</p>

      <form action="<?= urlTo('/login/login'); ?>" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="Username" placeholder="Username" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="Password" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <!-- Token reCAPTCHA v3 -->
        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response"> <!-- Token reCAPTCHA -->
        <div class="row">
          <div class="col-8"></div>
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
        </div>
      </form>
      <p class="mb-0">
        <a href="<?= urlTo('/login/register'); ?>" class="text-center">Belum punya akun?</a>
      </p>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="<?= urlTo('/public/adminlte/plugins/jquery/jquery.min.js'); ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?= urlTo('/public/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<!-- AdminLTE App -->
<script src="<?= urlTo('/public/adminlte/js/adminlte.min.js'); ?>"></script>

<!-- Google reCAPTCHA v3 -->
<script src="https://www.google.com/recaptcha/api.js?render=6LczyokqAAAAAAFu2cNq2B_y_nFX4970oZpcQi2q"></script>
<script>
  grecaptcha.ready(function() {
    grecaptcha.execute('6LczyokqAAAAAAFu2cNq2B_y_nFX4970oZpcQi2q', { action: 'login' }).then(function(token) {
      document.getElementById('g-recaptcha-response').value = token; // Isi token ke input tersembunyi
    });
  });
</script>

</body>
</html>
