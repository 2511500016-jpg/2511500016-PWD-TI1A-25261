<?php
session_start();

require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/fungsi.php';

/* =========================
   Cek method harus POST
   ========================= */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash_error'] = 'Akses tidak valid.';
    redirect_ke('read.php');
    exit;
}

/* =========================
   Validasi CID
   ========================= */
$cid = filter_input(INPUT_POST, 'cid', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
]);

if (!$cid) {
    $_SESSION['flash_error'] = 'CID tidak valid.';
    redirect_ke('read.php');
    exit;
}

/* =========================
   Ambil & sanitasi input
   ========================= */
$nama    = bersihkan($_POST['txtNama'] ?? '');
$email   = bersihkan($_POST['txtEmail'] ?? '');
$pesan   = bersihkan($_POST['txtPesan'] ?? '');
$captcha = bersihkan($_POST['txtCaptcha'] ?? '');

/* =========================
   Validasi input
   ========================= */
$errors = [];

if ($nama === '') {
    $errors[] = 'Nama wajib diisi.';
} elseif (strlen($nama) < 3) {
    $errors[] = 'Nama minimal 3 karakter.';
}

if ($email === '') {
    $errors[] = 'Email wajib diisi.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Format email tidak valid.';
}

if ($pesan === '') {
    $errors[] = 'Pesan wajib diisi.';
} elseif (strlen($pesan) < 10) {
    $errors[] = 'Pesan minimal 10 karakter.';
}

if ($captcha === '') {
    $errors[] = 'Captcha wajib diisi.';
} elseif ($captcha !== '6') {
    $errors[] = 'Jawaban captcha salah.';
}

/* =========================
   Jika ada error
   ========================= */
if ($errors) {
    $_SESSION['old'] = [
        'nama'  => $nama,
        'email' => $email,
        'pesan' => $pesan
    ];

    $_SESSION['flash_error'] = implode('<br>', $errors);
    redirect_ke('edit.php?cid=' . $cid);
    exit;
}

/* =========================
   UPDATE dengan prepared statement
   ========================= */
$stmt = mysqli_prepare(
    $conn,
    "UPDATE tbl_tamu
     SET cnama = ?, cemail = ?, cpesan = ?
     WHERE cid = ?"
);

if (!$stmt) {
    $_SESSION['flash_error'] = 'Terjadi kesalahan sistem.';
    redirect_ke('edit.php?cid=' . $cid);
    exit;
}

mysqli_stmt_bind_param($stmt, "sssi", $nama, $email, $pesan, $cid);

/* =========================
   Eksekusi
   ========================= */
if (mysqli_stmt_execute($stmt)) {

    unset($_SESSION['old']);

    $_SESSION['flash_sukses'] = 'Terima Kasih,Data berhasil diperbarui.';
    mysqli_stmt_close($stmt);

    redirect_ke('read.php');
    exit;
}

/* =========================
   Jika UPDATE gagal
   ========================= */
mysqli_stmt_close($stmt);

$_SESSION['old'] = [
    'nama'  => $nama,
    'email' => $email,
    'pesan' => $pesan
];

$_SESSION['flash_error'] = 'Data gagal diperbarui. Silakan coba lagi.';
redirect_ke('edit.php?cid=' . $cid);
exit;
