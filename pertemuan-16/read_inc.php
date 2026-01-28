<?php
require 'koneksi.php';

$fieldContact = [
  "kode_dosen" => ["label" => "Kode Dosen:", "suffix" => ""],
  "nama_dosen" => ["label" => "Nama Dosen:", "suffix" => ""],
  "alamat_rumah" => ["label" => "Alamat Rumah:", "suffix" => ""],
  "tanggal_jadi_dosen" => ["label" => "Tanggal Jadi Dosen:", "suffix" => ""],
  "jja_dosen" => ["label" => "JJA Dosen:", "suffix" => ""],
  "home_base_prodi" => ["label" => "Home Base Prodi:", "suffix" => ""],
  "nomor_hp" => ["label" => "Nomor HP:", "suffix" => ""],
  "nama_pasangan" => ["label" => "Nama Pasangan:", "suffix" => ""],
  "nama_anak" => ["label" => "Nama Anak:", "suffix" => ""],
  "bidang_ilmu_dosen" => ["label" => "Bidang Ilmu Dosen:", "suffix" => ""],
];

$sql = "SELECT * FROM tbl_dosen ORDER BY cid DESC";
$q = mysqli_query($conn, $sql);
if (!$q) {
  echo "<p>Gagal membaca data dosen: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
} elseif (mysqli_num_rows($q) === 0) {
  echo "<p>Belum ada data dosen yang tersimpan.</p>";
} else {
  while ($row = mysqli_fetch_assoc($q)) {
    $arrContact = [
      "kode_dosen" => $row["kode_dosen"] ?? "",
      "nama_dosen" => $row["nama_dosen"] ?? "",
      "alamat_rumah" => $row["alamat_rumah"] ?? "",
      "tanggal_jadi_dosen" => $row["tanggal_jadi_dosen"] ?? "",
      "jja_dosen" => $row["jja_dosen"] ?? "",
      "home_base_prodi" => $row["home_base_prodi"] ?? "",
      "nomor_hp" => $row["nomor_hp"] ?? "",
      "nama_pasangan" => $row["nama_pasangan"] ?? "",
      "nama_anak" => $row["nama_anak"] ?? "",
      "bidang_ilmu_dosen" => $row["bidang_ilmu_dosen"] ?? "",
    ];
    echo tampilkanBiodata($fieldContact, $arrContact);
  }
}
?>
