<?php
session_start();
require 'koneksi.php';
require 'fungsi.php';

/* =========================
   Validasi CID dari GET
   ========================= */
$cid = filter_input(INPUT_GET, 'cid', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
]);

if (!$cid) {
    $_SESSION['flash_error'] = 'Akses tidak valid.';
    redirect_ke('read.php');
    exit;
}

/* =========================
   Ambil data tamu
   ========================= */
$stmt = mysqli_prepare(
    $conn,
    "SELECT cnama, cemail, cpesan
     FROM tbl_tamu
     WHERE cid = ?"
);

if (!$stmt) {
    $_SESSION['flash_error'] = 'Query tidak benar.';
    redirect_ke('read.php');
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $cid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$row) {
    $_SESSION['flash_error'] = 'Record tidak ditemukan.';
    redirect_ke('read.php');
    exit;
}

/* =========================
   Prefill data
   ========================= */
$nama  = $row['cnama'];
$email = $row['cemail'];
$pesan = $row['cpesan'];

$flash_error = $_SESSION['flash_error'] ?? '';
$old = $_SESSION['old'] ?? [];

unset($_SESSION['flash_error'], $_SESSION['old']);

if ($old) {
    $nama  = $old['nama']  ?? $nama;
    $email = $old['email'] ?? $email;
    $pesan = $old['pesan'] ?? $pesan;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku Tamu</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<header>
    <h1>Ini Header</h1>
    <button class="menu-toggle" id="menuToggle" aria-label="Toggle navigation">☰</button>

    <nav>
        <ul>
            <li><a href="home">Beranda</a></li>
            <li><a href="about">Tentang</a></li>
            <li><a href="contact">Kontak</a></li>
        </ul>
    </nav>
</header>

<main>
<section id="contact">
    <h2>Edit Buku Tamu</h2>

    <?php if ($flash_error): ?>
        <div style="padding:10px; margin-bottom:10px;
        background:#f8d7da; color:#721c24; border-radius:6px;">
            <?= htmlspecialchars($flash_error); ?>
        </div>
    <?php endif; ?>

    <form action="proses_update.php" method="POST">

        <!-- WAJIB: kirim CID -->
     <input type="text" name="cid" value="<?= (int)$cid; ?>">

        <label>
            <span>Nama:</span>
            <input type="text" name="txtNama" required autocomplete="name"
                   value="<?= htmlspecialchars($nama); ?>">
        </label>

        <label>
            <span>Email:</span>
            <input type="email" name="txtEmail" required autocomplete="email"
        value="<?= htmlspecialchars($email); ?>">
        </label>

        <label>
            <span>Pesan Anda:</span>
            <textarea name="txtPesan" rows="4" required><?= htmlspecialchars($pesan); ?></textarea>
            <small id="charCount"><?= strlen($pesan); ?>/200 karakter</small>
        </label>

        <label>
            <span>Captcha 2 × 3 = ?</span>
            <input type="number" name="txtCaptcha" required>
        </label>

        <button type="submit">Kirim</button>
        <button type="reset">Batal</button>
        <a href="read.php" class="reset">Kembali</a>
    </form>
</section>
</main>

<script src="script.js"></script>
</body>
</html>
