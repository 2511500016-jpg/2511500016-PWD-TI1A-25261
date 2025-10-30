<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Belajar PHP Dasar</title>
</head>
<body>
  <h1><?php echo "Halo, Dunia PHP!"; ?></h1>
  <h2><?php echo "Selamat Datang Di PHP, Dimas Daffah"; ?></h2>

  <?php
  // =======================
  // Bagian 1: Variabel Dasar
  // =======================
  $nama = "Dimas";
  $umur = 19;
  $tinggi = 1.75;
  $aktif = true;

  echo "<h3>Data Diri</h3>";
  echo "Nama: $nama <br>";
  echo "Umur: $umur tahun <br>";
  echo "Tinggi: $tinggi meter <br>";
  echo "Status aktif: " . ($aktif ? "Ya" : "Tidak") . "<br><br>";

  echo "<pre>";
  var_dump($nama);
  var_dump($umur);
  var_dump($tinggi);
  var_dump($aktif);
  echo "</pre>";

  // =======================
  // Bagian 2: Tipe Data PHP
  // =======================
  $nama = "Dimas Daffah";
  $umur = 19;
  $tinggi = 1.75;
  $aktif = true;
  $hobi = ["Bermain bola", "Bermain game"];
  $mahasiswa = (object)[
    "nim" => "2511500016",
    "nama" => "Dimas Daffah",
    "prodi" => "Teknik Informatika"
  ];
  $nilai_akhir = null;

  echo "<h3>Demo Tipe Data PHP</h3>";
  echo "<pre>";
  echo "String:\n"; var_dump($nama);
  echo "\nInteger:\n"; var_dump($umur);
  echo "\nFloat:\n"; var_dump($tinggi);
  echo "\nBoolean:\n"; var_dump($aktif);
  echo "\nArray:\n"; var_dump($hobi);
  echo "\nObject:\n"; var_dump($mahasiswa);
  echo "\nNULL:\n"; var_dump($nilai_akhir);
  echo "</pre>";

  // =======================
  // Bagian 3: Konstanta
  // =======================
  define("KAMPUS", "ISB Atma Luhur");
  const ANGKATAN = 2025;

  echo "<h3>Konstanta</h3>";
  echo "Kampus: " . KAMPUS . "<br>";
  echo "Angkatan: " . ANGKATAN . "<br><br>";

  // =======================
  // Bagian 4: Operasi Aritmatika
  // =======================
  $a = 10;
  $b = 3;

  echo "<h3>Operasi Aritmatika</h3>";
  echo "\$a + \$b = " . ($a + $b) . "<br>";
  echo "\$a % \$b = " . ($a % $b) . "<br><br>";

  // =======================
  // Bagian 5: Perbandingan
  // =======================
  $a = 100;
  $b = "100";
  $c = 0;
  $d = false;

  echo "<h3>Perbandingan == dan ===</h3>";
  echo "\$a == \$b : "; var_dump($a == $b); echo "<br>";
  echo "\$a === \$b : "; var_dump($a === $b); echo "<br>";
  echo "\$c == \$d : "; var_dump($c == $d); echo "<br>";
  echo "\$c === \$d : "; var_dump($c === $d); echo "<br><br>";

  // =======================
  // Bagian 6: Struktur Kontrol
  // =======================
  $nilai = 80;
  echo "<h3>Penilaian</h3>";
  if ($nilai >= 90) {
    echo "Nilai Anda: A";
  } elseif ($nilai >= 80) {
    echo "Nilai Anda: B";
  } else {
    echo "Nilai Anda: C";
  }
  ?>

  <?php 
$hari = "Senin"; 
switch ($hari) { 
  case "Senin": echo "Awal Minggu!"; break; 
  case "Jumat": echo "Hampir weekend!"; break; 
  default: echo "Hari biasa."; 
} 
?> 
<?php 
$hobi = ["Coding", "Memasak", "Musik"]; 
foreach ($hobi as $item) { 
  echo "Hobi: $item <br>"; 
} 
?> 
Latihan array dan penggunaan print_r() dan var_dump() 
<?php 
$hobi = ["Game", "Memasak", "Musik", "Membaca", "Traveling"]; 
 
echo "<h3>Daftar Hobi Saya:</h3>"; 
for ($i = 0; $i < count($hobi); $i++) { 
  echo ($i + 1) . ". " . $hobi[$i] . "<br>"; 
} 
 
echo "<hr>"; 
echo "<h4>Hasil print_r():</h4>"; 
echo "<pre>"; 
print_r($hobi); 
echo "</pre>"; 
 
echo "<h4>Hasil var_dump():</h4>"; 
echo "<pre>"; 
var_dump($hobi); 
echo "</pre>"; 
?> 

<?php 
for ($i=1; $i<=5; $i++) { 
  echo "Perulangan ke-$i <br>"; 
} 
?> 
 
<?php 
$i = 1; 
do { 
  echo "Iterasi ke-$i<br>"; 
  $i++; 
} while (1 == 1); 
?>
</body>
</html>
