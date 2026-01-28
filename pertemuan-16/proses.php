<?php
session_start();
require __DIR__ . './koneksi.php';
require_once __DIR__ . '/fungsi.php';

#cek method form, hanya izinkan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  $_SESSION['flash_error'] = 'Akses tidak valid.';
  redirect_ke('index.php#contact');
}

#ambil dan bersihkan nilai dari form
$kode_dosen  = bersihkan($_POST['txtKodeDosen']  ?? '');
$nama = bersihkan($_POST['txtNamaDosen'] ?? '');
$alamat = bersihkan($_POST['txtAlamatRumah'] ?? '');
$tanggal = bersihkan($_POST['txtTanggalDosen'] ?? '');
$jja = bersihkan($_POST['txtJJA'] ?? '');
$prodi = bersihkan($_POST['txtProdi'] ?? '');
$nohp = bersihkan($_POST['txtNoHP'] ?? '');
$pasangan = bersihkan($_POST['txtNamaPasangan'] ?? '');
$anak = bersihkan($_POST['txtNamaAnak'] ?? '');
$ilmu = bersihkan($_POST['txtBidangIlmu'] ?? '');

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

if ($captcha!=="5") {
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
  redirect_ke('index.php#contact');
}

#menyiapkan query INSERT dengan prepared statement
$sql = "INSERT INTO tbl_dosen (ckode_dosen, cnama, calamat_rumah, ctanggal_jadi_dosen, cjja_dosen, chome_base_prodi, cno_hp, cnama_pasangan, cnama_anak, cbidang_ilmu_dosen) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
  #jika gagal prepare, kirim pesan error ke pengguna (tanpa detail sensitif)
  $_SESSION['flash_error'] = 'Terjadi kesalahan sistem (prepare gagal).';
  redirect_ke('index.php#contact');
}
#bind parameter dan eksekusi (s = string)
mysqli_stmt_bind_param($stmt, "ssssssssss", $kode_dosen, $nama, $alamat, $tanggal, $jja, $prodi, $nohp, $pasangan, $anak, $ilmu);

if (mysqli_stmt_execute($stmt)) { #jika berhasil, kosongkan old value, beri pesan sukses
  unset($_SESSION['old']);
  $_SESSION['flash_sukses'] = 'Terima kasih, data Anda sudah tersimpan.';
  redirect_ke('index.php#contact'); #pola PRG: kembali ke form / halaman home
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
  $_SESSION['flash_error'] = 'Data gagal disimpan. Silakan coba lagi.';
  redirect_ke('index.php#contact');
}
#tutup statement
mysqli_stmt_close($stmt);

$arrBiodata = [
  "kodedosen" => $_POST["txtKodeDosen"] ?? "",
  "nama" => $_POST["txtNmDosen"] ?? "",
  "alamat" => $_POST["txtAlamatRumah"] ?? "",
  "tanggal" => $_POST["txtTanggalDosen"] ?? "",
  "jja" => $_POST["txtJJA"] ?? "",
  "prodi" => $_POST["txtProdi"] ?? "",
  "nohp" => $_POST["txtNoHP"] ?? "",
  "pasangan" => $_POST["txNamaPasangan"] ?? "",
  "anak" => $_POST["txtNamaAnak"] ?? "",
  "ilmu" => $_POST["txtBidangIlmu"] ?? ""
];
$_SESSION["biodata"] = $arrBiodata;

header("location: index.php#about");
