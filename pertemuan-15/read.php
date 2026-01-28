<?php
session_start();
require 'koneksi.php';
require 'fungsi.php';

$sql = "SELECT * FROM tbl_dosen ORDER BY kode_dosen DESC";
$q = mysqli_query($conn, $sql);
if (!$q) {
    die("Query error: " . mysqli_error($conn));
}
?>

<?php
$flash_sukses = $_SESSION['flash_sukses_dosen'] ?? '';
$flash_error  = $_SESSION['flash_error_dosen'] ?? '';
unset($_SESSION['flash_sukses_dosen'], $_SESSION['flash_error_dosen']);
?>

<?php if (!empty($flash_sukses)): ?>
    <div style="padding:10px; margin-bottom:10px;
      background:#d4edda; color:#155724; border-radius:6px;">
      <?= $flash_sukses; ?>
    </div>
<?php endif; ?>

<?php if (!empty($flash_error)): ?>
    <div style="padding:10px; margin-bottom:10px;
      background:#f8d7da; color:#721c24; border-radius:6px;">
      <?= $flash_error; ?>
    </div>
<?php endif; ?>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Kode Dosen</th>
        <th>Nama Dosen</th>
        <th>Alamat Rumah</th>
        <th>Tanggal Jadi Dosen</th>
        <th>JJA Dosen</th>
        <th>Home Base Prodi</th>
        <th>No HP</th>
        <th>Nama Pasangan</th>
        <th>Nama Anak</th>
        <th>Bidang Ilmu</th>
    </tr>

    <?php $i = 1; ?>
    <?php while ($row = mysqli_fetch_assoc($q)): ?>
    <tr>
        <td><?= $i++ ?></td>
        <td>
            <a href="edit_dosen.php?kode_dosen=<?= htmlspecialchars($row['kode_dosen']); ?>">Edit</a>
            <a onclick="return confirm('Hapus <?= htmlspecialchars($row['nama_dosen']); ?>?')"
               href="hapus_dosen.php?kode_dosen=<?= htmlspecialchars($row['kode_dosen']); ?>">
               Delete
            </a>
        </td>
        <td><?= htmlspecialchars($row['kode_dosen']); ?></td>
        <td><?= htmlspecialchars($row['nama_dosen']); ?></td>
        <td><?= htmlspecialchars($row['alamat_rumah']); ?></td>
        <td><?= date('d-m-Y', strtotime($row['tanggal_jadi_dosen'])); ?></td>
        <td><?= htmlspecialchars($row['jja_dosen']); ?></td>
        <td><?= htmlspecialchars($row['home_base_prodi']); ?></td>
        <td><?= htmlspecialchars($row['nomor_hp']); ?></td>
        <td><?= htmlspecialchars($row['nama_pasangan'] ?? '-'); ?></td>
        <td><?= htmlspecialchars($row['nama_anak'] ?? '-'); ?></td>
        <td><?= htmlspecialchars($row['bidang_ilmu_dosen']); ?></td>
    </tr>
    <?php endwhile; ?>
</table>
