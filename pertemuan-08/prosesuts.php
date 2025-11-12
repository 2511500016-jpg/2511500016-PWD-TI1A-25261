<?php
session_start();
$sesnim = $_POST["txtNim"];
$sesnamalengkap = $_POST["txtNamalengkap"];
$sestempatlahir = $_POST["txtTempatlahir"];
$seshobi = $_POST["txtHobi"];
$sespasangan = $_POST["txtPasangan"];
$sespekerjaan = $_POST["txtPekerjaan"];
$sesnamaorangtua = $_POST["txtNamaorangtua"];
$sesNamakakak = $_POST["txtNamakakak"];
$sesNamaadik = $_POST["txtNamaadik"];

$_SESSION["sesnim"] = $sesnim;
$_SESSION["sesNamalengkap"] = $Namalengkap;
$_SESSION["sesTempatlahir"] = $Tempatlahir;
$_SESSION["sesHobi"] = $sesHobi;
$_SESSION["sesPasangan"] = $Pasangan;
$_SESSION["txtPekerjaan"] = $Pekerjaan;
$_SESSION["txtNamaorangtua"] = $Namaorangtua;
$_SESSION["txtNamakakak"] = $Namakakak;
$_SESSION["txtNamaadik"] = $Namaadik;

header("location: index.php");
?>