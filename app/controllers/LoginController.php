<?php
class LoginController extends Controller
{
  public function index()
  {
    $this->view('login');
  }

  public function login()
  {
    // Ambil Site Key dan Secret Key reCAPTCHA
    // $secretKey = "6LczyokqAAAAAKNIlGV7pczGkQjZb_mwtEZxUjj1"; // Ganti dengan Secret Key reCAPTCHA v3 Anda
    // $captchaResponse = $_POST['g-recaptcha-response']; // Token yang dikirim dari frontend
    // $remoteIP = $_SERVER['REMOTE_ADDR'];

    // // Validasi token reCAPTCHA dengan Google
    // $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captchaResponse&remoteip=$remoteIP");
    // $responseKeys = json_decode($response, true);

    // // Periksa apakah CAPTCHA valid
    // if (!$responseKeys['success'] || $responseKeys['score'] < 0.5 || $responseKeys['action'] !== 'login') {
    //   redirectTo('error', 'CAPTCHA tidak valid. Silakan coba lagi.', '/login');
    //   return;
    // }

    // CAPTCHA valid, lanjutkan proses login
    $Username = $_POST['Username'];
    $Password = $_POST['Password'];
    $data = $this->model('User')->getByUsername($Username);

    // Periksa ketersediaan username
    if (!empty($data)) {
      // Periksa kecocokan password
      if (password_verify($Password, $data['Password'])) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $data['Username'];
        $_SESSION['role'] = $data['Role'];
        $_SESSION['UserID'] = $data['UserID'];
        redirectTo('success', 'Selamat datang kembali!', '/');
      } else {
        redirectTo('error', 'Maaf, Password salah!', '/login');
      }
    } else {
      redirectTo('error', 'Maaf, Username tidak terdaftar!', '/login');
    }
  }

  public function register()
  {
    $this->view('register');
  }

  public function registers()
  {

    if (strlen($_POST['Password']) >= 8) {
      redirectTo('success', 'Selamat, Registrasi berhasil', '/login/register');
    } else {
      redirectTo('error', 'Maaf, Password harus memiliki minimal 8 karakter!', '/login/register');
    }

    if ($_POST['Password'] !== $_POST['PasswordConfirm']) {
      redirectTo('error', 'Maaf, Konfirmasi password tidak cocok!', '/login/register');
    } else {
      if (
        $this->model('User')->create([
          'Username' => $_POST['Username'],
          'Email' => $_POST['Email'],
          'NamaLengkap' => $_POST['NamaLengkap'],
          'Alamat' => $_POST['Alamat'],
          'no_hp' => $_POST['no_hp'],
          'Password' => password_hash($_POST['Password'], PASSWORD_DEFAULT),
          'Role' => 3
        ]) > 0
      ) {
        redirectTo('success', 'Selamat, Registrasi berhasil', '/login');
      } else {
        redirectTo('error', 'Maaf, Username/Email sudah terdaftar', '/login/register');
      }
    }
  }

  public function logout()
  {
    session_destroy();
    session_unset();
    redirectTo('success', 'Selamat, Anda berhasil logout!', '/login');
  }
}
