<?php
session_start();      // Memulai session agar bisa dihapus
session_destroy();    // Menghapus semua data session
header("Location: post.php"); // Mengarahkan kembali ke halaman post.php
exit;                 // Opsional tapi disarankan, untuk menghentikan eksekusi script
?>