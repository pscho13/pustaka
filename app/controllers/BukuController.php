<?php
use Dompdf\Dompdf;
class BukuController extends Controller
{
  public function __construct()
  {
    /**
     * Batasi hak akses hanya untuk Administrator dan Petugas
     * Selain Administrator dan Petugas akan langsung diarahkan kembali ke halaman home
     */
    if ($_SESSION['role'] !== 'Administrator' && $_SESSION['role'] !== 'Petugas') {
      redirectTo('error', 'Mohon maaf, Anda tidak berhak mengakses halaman ini', '/');
    }
  }

  public function index()
  {
    $data = $this->model('KBRelasi')->get();
    $this->view('buku/home', $data);
  }

  public function create()
  {
    $data = $this->model('KategoriBuku')->getAll();
    $this->view('buku/create', $data);
  }

  // public function store()
  // {
  //   $BukuID = $this->model('Buku')->create([
  //     'Judul'       => $_POST['Judul'],
  //     'Penulis'     => $_POST['Penulis'],
  //     'Penerbit'    => $_POST['Penerbit'],
  //     'TahunTerbit' => $_POST['TahunTerbit'],
  //     'Cover' => $_POST['Cover'],
  //   ]);

  //   $KategoriID = $_POST['KategoriID'];

  //   if ($this->model('KBRelasi')->create([
  //     'BukuID'      => $BukuID,
  //     'KategoriID'  => $KategoriID
  //   ]) > 0) {
  //     redirectTo('success', 'Selamat, Buku berhasil di tambahkan', '/buku');
  //   } else {
  //     redirectTo('error', 'Maaf, Buku gagal di tambahkan', '/buku/create');
  //   }
  // }

  public function store()
  {
    // Memproses upload cover
    $coverPath = '';
    if (isset($_FILES['Cover']) && $_FILES['Cover']['error'] === UPLOAD_ERR_OK) {
      $fileTmpPath = $_FILES['Cover']['tmp_name'];
      $fileName = $_FILES['Cover']['name'];
      $fileSize = $_FILES['Cover']['size'];
      $fileType = $_FILES['Cover']['type'];

      // Validasi file
      $allowedfileExtensions = array('jpg', 'jpeg', 'png', 'gif');
      $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

      if (in_array($fileExtension, $allowedfileExtensions) && $fileSize < 2000000) { // Maksimal 2MB
        $uploadFileDir = dirname(__DIR__) . '/../public/img/'; // Path folder tujuan Anda

        // Menghasilkan nama file unik
        $newFileName = uniqid('cover_', true) . '.' . $fileExtension; // Menambahkan prefix dan ekstensi file
        $dest_path = $uploadFileDir . $newFileName;

        // Memindahkan file ke direktori tujuan
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
          $coverPath = 'public/img/' . $newFileName; // Simpan path relatif file yang di-upload
        } else {
          error_log("Gagal memindahkan file: " . print_r($_FILES['Cover'], true));
          error_log("Path tujuan: " . $dest_path);
          error_log("Izin folder: " . var_export(is_writable($uploadFileDir), true));
          // redirectTo('error', 'Maaf, Gagal mengupload cover buku', '/buku/create');
          return; // Hentikan eksekusi lebih lanjut
        }
      } else {
        redirectTo('error', 'Format file tidak valid atau ukuran terlalu besar', '/buku/create');
        return; // Hentikan eksekusi lebih lanjut
      }
    } else {
      redirectTo('error', 'Tidak ada file yang di-upload', '/buku/create');
      return; // Hentikan eksekusi lebih lanjut
    }

    // Menyimpan data buku ke database
    $BukuID = $this->model('Buku')->create([
      'Judul' => $_POST['Judul'],
      'Penulis' => $_POST['Penulis'],
      'Penerbit' => $_POST['Penerbit'],
      'TahunTerbit' => $_POST['TahunTerbit'],
      'updated_by' => $_POST['updated_by'],
      'Cover' => $coverPath, // Simpan path cover buku
    ]);

    $KategoriID = $_POST['KategoriID'];

    // Menyimpan relasi buku dengan kategori
    if (
      $this->model('KBRelasi')->create([
        'BukuID' => $BukuID,
        'KategoriID' => $KategoriID
      ]) > 0
    ) {
      redirectTo('success', 'Selamat, Buku berhasil di tambahkan', '/buku');
    } else {
      redirectTo('error', 'Maaf, Buku gagal di tambahkan', '/buku/create');
    }
  }



  public function edit($id)
  {
    $data = $this->model('Buku')->getById($id);
    $this->view('buku/edit', $data);
  }

  public function update($id)
  {
    if ($this->model('Buku')->update($id) > 0) {
      redirectTo('success', 'Selamat, Data Buku Berhasil di Update', '/buku');
    } else {
      redirectTo('danger', 'Maaf, Data Buku gagal di Update', '/buku');
    }
  }

  public function delete($id)
  {
    if ($this->model('Buku')->delete($id) > 0) {
      redirectTo('success', 'Selamat, Data Buku berhasil di hapus!', '/buku');
    }
  }

  public function ulasan($id)
  {
    $this->view('buku/ulasan', [
      'buku' => $this->model('Buku')->getById($id),
      'ulasan' => $this->model('Ulasanbuku')->getByBookId($id)
    ]);
  }
  public function cetakbuku()
  {
    $data = $this->model('Peminjaman')->get();
    $html = "
    <style>
        .header {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }
        .header img {
            position: absolute;
            top: 0;
            width: 100px;
            height: auto;
        }
        .header .left-img {
            left: 0;
        }
        .header .right-img {
            right: 0;
        }
        .header h1, .header h3 {
            margin: 0;
        }
        .header p {
            margin-top: 5px;
        }
        .table-container {
            text-align: center;
        }
        table {
            margin: auto;
            border-collapse: collapse;
            width: 80%;
        }
        th, td {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
            padding-right: 50px;
        }
        .signature p {
            margin: 5px 0;
        }
    </style>
    <div class='header'>
        <img src='assets/LogoProvinsi.png' class='left-img' alt='Left Image' />
        <img src='assets/LogoSekolah.png' class='right-img' alt='Right Image' />
        <h1>SMK 4 PAYAKUMBUH</h1>
        <h3>PERPUSTAKAAN DIGITAL</h3>
        <h3>DAFTAR BUKU</h3>
        <p>Alamat, Payakumbuh, Sumatera Barat</p>
        <hr>
    </div>
    <div class='table-container'>
        <table>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Judul Buku</th>
                <th>Tanggal Peminjaman</th>
                <th>Tanggal Pengembalian</th>
                <th>Denda</th>
            </tr>";

    $no = 1;
    foreach ($data as $buku) {
      $html .= "
            <tr>
                <td>{$no}</td>
                <td>{$buku['NamaLengkap']}</td>
                <td>{$buku['Judul']}</td>
                <td>{$buku['TanggalPeminjaman']}</td>
                <td>{$buku['TanggalPengembalian']}</td>
                <td>{$buku['denda']}</td>
            </tr>";
      $no++;
    }

    $html .= "
        </table>
    </div>
    <div class='signature'>
        <p>Payakumbuh, " . date('d-m-Y') . "</p>
        <p>Petugas Perpustakaan</p>
        <br><br><br>
        
        <p>_________________________</p>
    </div>";

    // Menggunakan Dompdf untuk generate PDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream('Data Buku', ['Attachment' => 0]);
  }

}
