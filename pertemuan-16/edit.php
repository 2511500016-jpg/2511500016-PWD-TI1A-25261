<?php
  session_start();
  require 'koneksi.php';
  require 'fungsi.php';

  /*
    Ambil nilai cid dari GET dan lakukan validasi untuk 
    mengecek cid harus angka dan lebih besar dari 0 (> 0).
    'options' => ['min_range' => 1] artinya cid harus ≥ 1 
    (bukan 0, bahkan bukan negatif, bukan huruf, bukan HTML).
  */
  $cid = filter_input(INPUT_GET, 'cid', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
  ]);
  /*
    Skrip di atas cara penulisan lamanya adalah:
    $cid = $_GET['cid'] ?? '';
    $cid = (int)$cid;

    Cara lama seperti di atas akan mengambil data mentah 
    kemudian validasi dilakukan secara terpisah, sehingga 
    rawan lupa validasi. Untuk input dari GET atau POST, 
    filter_input() lebih disarankan daripada $_GET atau $_POST.
  */

  /*
    Cek apakah $cid bernilai valid:
    Kalau $cid tidak valid, maka jangan lanjutkan proses, 
    kembalikan pengguna ke halaman awal (read.php) sembari 
    mengirim penanda error.
  */
  if (!$cid) {
    $_SESSION['flash_error'] = 'Akses tidak valid.';
    redirect_ke('read.php');
  }

  /*
    Ambil data lama dari DB menggunakan prepared statement, 
    jika ada kesalahan, tampilkan penanda error.
  */
  $stmt = mysqli_prepare($conn, "SELECT ckode_dosen, cnama, calamat, ctanggal_jadi_dosen, cjja_dosen, chome_base_prodi, cno_hp, cnama_pasangan, cnama_anak, cbidang_ilmu_dosen
                                    FROM tbl_dosen WHERE cid = ? LIMIT 1");
  if (!$stmt) {
    $_SESSION['flash_error'] = 'Query tidak benar.';
    redirect_ke('read.php');
  }

  mysqli_stmt_bind_param($stmt, "i", $cid);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($res);
  mysqli_stmt_close($stmt);

  if (!$row) {
    $_SESSION['flash_error'] = 'Record tidak ditemukan.';
    redirect_ke('read.php');
  }

  #Nilai awal (prefill form)
  $kode_dosen  = $row['ckode_dosen'] ?? '';
  $nama = $row['cnama'] ?? '';
  $alamat = $row['calamat'] ?? '';
  $tanggal = date('Y-m-d', strtotime($row['ctanggal_jadi_dosen'])) ?? '';
  $jja = $row['cjja_dosen'] ?? '';
  $prodi = $row['chome_base_prodi'] ?? '';
  $nohp = $row['cno_hp'] ?? '';
  $pasangan = $row['cnama_pasangan'] ?? '';
  $anak = $row['cnama_anak'] ?? '';
  $ilmu = $row['cbidang_ilmu_dosen'] ?? '';

  #Ambil error dan nilai old input kalau ada
  $flash_error = $_SESSION['flash_error'] ?? '';
  $old = $_SESSION['old'] ?? [];
  unset($_SESSION['flash_error'], $_SESSION['old']);
  if (!empty($old)) {
    $kode_dosen = $old['kode_dosen'] ?? $kode_dosen;
    $nama = $old['nama'] ?? $nama;
    $alamat = $old['alamat'] ?? $alamat;
    $tanggal = $old['tanggal'] ?? $tanggal;
    $jja = $old['jja'] ?? $jja;
    $prodi = $old['prodi'] ?? $prodi;
    $nohp = $old['nohp'] ?? $nohp;
    $pasangan = $old['pasangan'] ?? $pasangan;
    $anak = $old['anak'] ?? $anak;
    $ilmu = $old['ilmu'] ?? $ilmu;
  }
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Judul Halaman</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <header>
      <h1>Ini Header</h1>
      <button class="menu-toggle" id="menuToggle" aria-label="Toggle Navigation">
        &#9776;
      </button>
      <nav>
        <ul>
          <li><a href="#home">Beranda</a></li>
          <li><a href="#about">Tentang</a></li>
          <li><a href="#contact">Kontak</a></li>
        </ul>
      </nav>
    </header>

    <main>
      <section id="contact">
        <h2>Edit Buku Tamu</h2>
        <?php if (!empty($flash_error)): ?>
          <div style="padding:10px; margin-bottom:10px; 
            background:#f8d7da; color:#721c24; border-radius:6px;">
            <?= $flash_error; ?>
          </div>
        <?php endif; ?>
        <form action="proses_update.php" method="POST">

          <input type="text" name="cid" value="<?= (int)$cid; ?>">

          <label for="txtKodeDosen"><span>Kode Dosen:</span>
            <input type="text" id="txtKodeDosen" name="txtKodeDosenEd" 
              placeholder="Masukkan kode dosen" required autocomplete="name"
              value="<?= !empty($kode_dosen) ? $kode_dosen : '' ?>">
          </label>

          <label for="txtNama"><span>Nama:</span>
            <input type="text" id="txtNama" name="txtNamaEd" 
              placeholder="Masukkan nama" required autocomplete="name"
              value="<?= !empty($nama) ? $nama : '' ?>">
          </label>

          <label for="txtAlamat"><span>Alamat:</span>
            <input type="text" id="txtAlamat" name="txtAlamatEd" 
              placeholder="Masukkan alamat" required autocomplete="name"
              value="<?= !empty($alamat) ? $alamat : '' ?>">
          </label>

          <label for="txtTanggal"><span>Tanggal Jadi Dosen:</span>
            <input type="date" id="txtTanggal" name="txtTanggalEd" 
              placeholder="Masukkan tanggal jadi dosen" required
              value="<?= !empty($tanggal) ? $tanggal : '' ?>">
          </label>

          <label for="txtJJA"><span>JJA:</span>
            <input type="text" id="txtJJA" name="txtJJAE" 
              placeholder="Masukkan JJA" required autocomplete="name"
              value="<?= !empty($jja) ? $jja : '' ?>">
          </label>

          <label for="txtProdi"><span>Home Base Prodi:</span>
            <input type="text" id="txtProdi" name="txtProdiEd" 
              placeholder="Masukkan home base prodi" required autocomplete="name"
              value="<?= !empty($prodi) ? $prodi : '' ?>">
          </label>

          <label for="txtNoHP"><span>No HP:</span>
            <input type="text" id="txtNoHP" name="txtNoHPEd" 
              placeholder="Masukkan no hp dosen" required autocomplete="name"
              value="<?= !empty($nohp) ? $nohp : '' ?>">
          </label>

          <label for="txtPasangan"><span>Nama Pasangan:</span>
            <input type="text" id="txtPasangan" name="txtNamaPasanganEd"
              placeholder="" required autocomplete=""
              value="<?= !empty($pasangan) ? $pasangan : '' ?>">
          </label>

          <label for="txtAnak"><span>Nama Anak:</span>
            <input type="text" id="txtAnak" name="txtNamaAnak
              placeholder="" required autocomplete=""
              value="<?= !empty($anak) ? $anak : '' ?>">
          </label>
          <label for="txtBidangIlmu"><span>Bidang Ilmu Dosen:</span>
            <input type="text" id="txtBidangIlmu" name="txtBidangIlmuEd" 
              placeholder="Masukkan bidang ilmu dosen" required autocomplete="name"
              value="<?= !empty($ilmu) ? $ilmu : '' ?>">

          <button type="submit">Kirim</button>
          <button type="reset">Batal</button>
          <a href="read.php" class="reset">Kembali</a>
        </form>
      </section>
    </main>

    <script src="script.js"></script>
  </body>
</html>