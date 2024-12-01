<?php

class PeminjamanController extends Controller
{
  public function index()
  {
    $data = $this->model('Peminjaman')->getPinjam();

    foreach ($data as &$buku) {
      if ($buku['StatusPeminjaman'] === 'Belum di Kembalikan') {
        $tanggalJatuhTempo = $buku['TanggalJatuhTempo'];
        $tanggalSekarang = date('Y-m-d');

        // Hitung selisih hari keterlambatan
        $selisihHari = (strtotime($tanggalSekarang) - strtotime($tanggalJatuhTempo)) / (60 * 60 * 24);

        // Tentukan denda per hari
        $dendaPerHari = 1000;

        // Jika keterlambatan lebih dari 0 hari, hitung denda
        $buku['denda'] = ($selisihHari > 0) ? $selisihHari * $dendaPerHari : 0;
      } else {
        // Jika buku sudah dikembalikan maka set denda menjadi 0
        $buku['denda'] = 0;
      }
    }

    $this->view('peminjaman/home', $data);
  }

  public function pinjam($id)
  {
    $data = $this->model('Buku')->getById($id);
    $this->view('peminjaman/pinjam', $data);
  }

  public function store()
  {
    $tanggalPeminjaman = date('Y-m-d'); // Tanggal peminjaman saat ini
    $tanggalJatuhTempo = date('Y-m-d', strtotime('+2 weeks', strtotime($tanggalPeminjaman))); // Tanggal jatuh tempo

    if (
      $this->model('Peminjaman')->create([
        'UserID' => $_SESSION['UserID'],
        'BukuID' => $_POST['BukuID'],
        'TanggalPeminjaman' => $tanggalPeminjaman,
        'TanggalJatuhTempo' => $tanggalJatuhTempo,
        'StatusPeminjaman' => 'Belum di Kembalikan'
      ]) > 0
    ) {
      redirectTo('success', 'Selamat, Buku berhasil di pinjam', '/peminjaman');
    } else {
      redirectTo('error', 'Maaf, Buku gagal di pinjam', '/peminjaman');
    }
  }

  // public function kembalikan($id)
  // {
  //   if ($this->model('Peminjaman')->update($id) > 0) {
  // 		redirectTo('success', 'Selamat, Buku berhasil di kembalikan!', '/peminjaman');
  // 	} else {
  // 		redirectTo('error', 'Maaf, Buku gagal di kembalikan!', '/peminjaman');
  // 	}
  // }

  public function kembalikan($id)
  {
    $tanggalPengembalian = date('Y-m-d'); // Tanggal pengembalian
    $tanggalJatuhTempo = $_POST['TanggalJatuhTempo'];
    $dendaPerHari = 0; // Denda per hari

    // Hitung jumlah hari keterlambatan
    $selisihHari = (strtotime($tanggalPengembalian) - strtotime($tanggalJatuhTempo)) / (60 * 60 * 24);
    $denda = $selisihHari > 0 ? $selisihHari * $dendaPerHari : 0;

    // Update data peminjaman
    $_POST = [
      'TanggalPengembalian' => $tanggalPengembalian,
      'StatusPeminjaman' => 'Sudah di Kembalikan',
      'Denda' => $denda
    ];

    // Panggil fungsi update
    if ($this->model('Peminjaman')->update($id) > 0) {
      $message = $denda > 0
        ? "Buku berhasil dikembalikan. Anda memiliki denda sebesar Rp$denda."
        : "Buku berhasil dikembalikan tanpa denda.";
      redirectTo('success', $message, '/peminjaman');
    } else {
      redirectTo('error', 'Maaf, Buku gagal dikembalikan!', '/peminjaman');
    }
  }

}