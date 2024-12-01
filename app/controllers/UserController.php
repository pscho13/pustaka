<?php
class UserController extends Controller
{
  public function __construct()
  {
    /**
     * Batasi hak akses hanya untuk Administrator
     * Selain Administrator akan langsung diarahkan kembali ke halaman home
     */
    if ($_SESSION['role'] !== 'Administrator') {
      redirectTo('error', 'Mohon maaf, Anda tidak berhak mengakses halaman ini', '/');
    }
  }

  public function index()
  {
    $data = $this->model('user')->getAll();
    $this->view('user/home', $data);
  }

  public function create()
  {
    $this->view('user/create');
  }

  // public function store()
  // {
  //   if ($_POST['Password'] !== $_POST['Konfirmasi_Password']) {
  //     redirectTo('error', 'Maaf, Konfirmasi password tidak cocok!', '/user/create');
  //   } else {
  //     if ($this->model('User')->create([
  //       'Username'      => $_POST['Username'],
  //       'Email'         => $_POST['Email'],
  //       'NamaLengkap'   => $_POST['NamaLengkap'],
  //       'Alamat'        => $_POST['Alamat'],
  //       'Password'      => password_hash($_POST['Password'], PASSWORD_DEFAULT),
  //       'Role'          => 2
  //     ]) > 0) {
  //       redirectTo('success', 'Selamat, Data Petugas Berhasil di Tambahkan', '/user');
  //     } else {
  //       redirectTo('error', 'Maaf, Username/Email sudah terdaftar', '/user');
  //     }
  //   }
  // }

  public function store()
  {
    $password = $_POST['Password'];
    $konfirmasiPassword = $_POST['Konfirmasi_Password'];

    // Validasi panjang password
    if (strlen($password) >= 8) {
      redirectTo('error', 'Maaf, Password harus memiliki minimal 8 karakter!', '/user/create');
    }

    // Validasi konfirmasi password
    if ($password !== $konfirmasiPassword) {
      redirectTo('error', 'Maaf, Konfirmasi password tidak cocok!', '/user/create');
    }

    // Jika validasi lolos, simpan data
    if (
      $this->model('User')->create([
        'Username' => $_POST['Username'],
        'Email' => $_POST['Email'],
        'NamaLengkap' => $_POST['NamaLengkap'],
        'Alamat' => $_POST['Alamat'],
        'no_hp' => $_POST['No HP'],
        'Password' => password_hash($password, PASSWORD_DEFAULT),
        'Role' => 2
      ]) > 0
    ) {
      redirectTo('success', 'Selamat, Data Petugas Berhasil di Tambahkan', '/user');
    } else {
      redirectTo('error', 'Maaf, Username/Email sudah terdaftar', '/user');
    }
  }

  public function edit($id)
  {
    $data = $this->model('User')->getById($id);
    $this->view('user/edit', $data);
  }

  public function update($id)
  {
    if ($this->model('User')->update($id) > 0) {
      redirectTo('success', 'Selamat, Data Petugas berhasil di edit!', '/user');
    } else {
      redirectTo('info', 'Tidak ada perubahan data!', '/user');
    }
  }

  public function delete($id)
  {
    if ($this->model('User')->delete($id) > 0) {
      redirectTo('success', 'Selamat, Data User berhasil di hapus!', '/user');
    }
  }
}
