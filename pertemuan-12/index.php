<?php
// =========================
// PROSES SUBMIT FORM
// =========================
session_start();

$error = "";
$nama = $email = $pesan = "";

// buat captcha
if (!isset($_SESSION['captcha'])) {
    $_SESSION['captcha'] = 2 * 3; // captcha statis sesuai soal
}

if (isset($_POST['kirim'])) {
    $nama   = htmlspecialchars($_POST['nama']);
    $email  = htmlspecialchars($_POST['email']);
    $pesan  = htmlspecialchars($_POST['pesan']);
    $captcha = $_POST['captcha'];

    if ($captcha != $_SESSION['captcha']) {
        $error = "Jawaban captcha salah.";
    } else {
        // contoh simpan ke database (opsional)
        // include 'koneksi.php';
        // mysqli_query($koneksi, "INSERT INTO buku_tamu VALUES('', '$nama', '$email', '$pesan')");

        // reset captcha
        unset($_SESSION['captcha']);
        echo "<script>alert('Data berhasil dikirim');</script>";
    }
}
?><!DOCTYPE html><html>
<head>
    <title>Edit Buku Tamu</title>
    <style>
        body { font-family: Arial; }
        .container { width: 400px; margin: auto; }