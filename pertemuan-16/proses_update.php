<?php
  session_start();
  require __DIR__ . '/koneksi.php';
  require_once __DIR__ . '/fungsi.php';

  #cek method form, hanya izinkan POST
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash_error'] = 'Akses tidak valid.';
    redirect_ke('read.php');
  }

  #validasi cid wajib angka dan > 0
  $cid = filter_input(INPUT_POST, 'cid', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
  ]);

  if (!$cid) {
    $_SESSION['flash_error'] = 'CID Tidak Valid.';
    redirect_ke('edit.php?cid='. (int)$cid);
  }

  #ambil dan bersihkan (sanitasi) nilai dari form
  $kode_dosen  = bersihkan($_POST['txtKodeDosenEd']  ?? '');
  $nama = bersihkan($_POST['txtNamaEd'] ?? '');
  $alamat = bersihkan($_POST['txtAlamatEd'] ?? '');
  $tanggal = bersihkan($_POST['txtTanggalEd'] ?? '');
  $jja = bersihkan($_POST['txtJJAE'] ?? '');
  $prodi = bersihkan($_POST['txtProdiEd'] ?? '');
  $nohp = bersihkan($_POST['txtNoHPEd'] ?? '');
  $pasangan = bersihkan($_POST['txtNamaPasanganEd'] ?? '');
  $anak = bersihkan($_POST['txtNamaAnakEd'] ?? '');
  $ilmu = bersihkan($_POST['txtBidangIlmuEd'] ?? '');
  #Validasi sederhana
  $errors = []; #ini array untuk menampung semua error yang ada
if ($kode_dosen === '') {
  $errors[] = 'Kode Dosen wajib diisi.';
}

if ($nama === '') {
  $errors[] = 'Nama Dosen wajib diisi.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $errors[] = 'Format e-mail tidak valid.';
}

if ($alamat === '') {
  $errors[] = 'Alamat Rumah wajib diisi.';
}

if ($tanggal === '') {
  $errors[] = 'Tanggal Jadi Dosen wajib diisi.';
}

if ($jja === '') {
  $errors[] = 'JJA wajib diisi.';
}

if ($prodi === '') {
  $errors[] = 'Home Base Prodi wajib diisi.';
}

if ($nohp === '') {
  $errors[] = 'No HP wajib diisi.';
}

if ($pasangan === '') {
  $errors[] = 'Nama Pasangan wajib diisi.';
}

if ($anak === '') {
  $errors[] = 'Nama Anak wajib diisi.';
}
if ($ilmu === '') {
  $errors[] = 'Bidang Ilmu Dosen wajib diisi.';
}


  if (mb_strlen($nama) < 3) {
    $errors[] = 'Nama minimal 3 karakter.';
  }

  if (mb_strlen($pesan) < 10) {
    $errors[] = 'Pesan minimal 10 karakter.';
  }

  if ($captcha!=="6") {
    $errors[] = 'Jawaban '. $captcha.' captcha salah.';
  }

  /*
  kondisi di bawah ini hanya dikerjakan jika ada error, 
  simpan nilai lama dan pesan error, lalu redirect (konsep PRG)
  */
  if (!empty($errors)) {
    $_SESSION['old'] = [
      'kode_dosen'  => $kode_dosen,
      'nama' => $nama,
      'alamat' => $alamat,
      'tanggal' => $tanggal,
      'jja' => $jja,
      'prodi' => $prodi,
      'nohp' => $nohp,
      'pasangan' => $pasangan,
      'anak' => $anak,
      'ilmu' => $ilmu
    ];

    $_SESSION['flash_error'] = implode('<br>', $errors);
    redirect_ke('edit.php?cid='. (int)$cid);
  }

  /*
    Prepared statement untuk anti SQL injection.
    menyiapkan query UPDATE dengan prepared statement 
    (WAJIB WHERE cid = ?)
  */
  $stmt = mysqli_prepare($conn, "UPDATE tbl_dosen 
                                SET ckode_dosen = ?, cnama = ?, calamat = ?, ctanggal = ?, cjja = ?, cprodi = ?, cnohp = ?, cpasangan = ?, canak = ?, cilmu = ? 
                                WHERE cid = ?");
  if (!$stmt) {
    #jika gagal prepare, kirim pesan error (tanpa detail sensitif)
    $_SESSION['flash_error'] = 'Terjadi kesalahan sistem (prepare gagal).';
    redirect_ke('edit.php?cid='. (int)$cid);
  }

  #bind parameter dan eksekusi (s = string, i = integer)
  mysqli_stmt_bind_param($stmt, "sssi", $nama, $email, $pesan, $cid);

  if (mysqli_stmt_execute($stmt)) { #jika berhasil, kosongkan old value
    unset($_SESSION['old']);
    /*
      Redirect balik ke read.php dan tampilkan info sukses.
    */
    $_SESSION['flash_sukses'] = 'Terima kasih, data Anda sudah diperbaharui.';
    redirect_ke('read.php'); #pola PRG: kembali ke data dan exit()
  } else { #jika gagal, simpan kembali old value dan tampilkan error umum
    $_SESSION['old'] = [
      'kode_dosen'  => $kode_dosen,
      'nama' => $nama,
      'alamat' => $alamat,
      'tanggal' => $tanggal,
      'jja' => $jja,
      'prodi' => $prodi,
      'nohp' => $nohp,
      'pasangan' => $pasangan,
      'anak' => $anak,
      'ilmu' => $ilmu
    ];
    $_SESSION['flash_error'] = 'Data gagal diperbaharui. Silakan coba lagi.';
    redirect_ke('edit.php?cid='. (int)$cid);
  }
  #tutup statement
  mysqli_stmt_close($stmt);

  redirect_ke('edit.php?cid='. (int)$cid);