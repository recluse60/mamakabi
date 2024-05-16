<?php
session_start();
session_unset(); // Tüm oturum değişkenlerini serbest bırak
session_destroy(); // Oturumu sonlandır
header("Location: index.php"); // Giriş sayfasına yönlendir
exit();
?>
