<?php
session_start();
$_SESSION = array();
session_unset();

session_destroy();
echo "<script>alert('Anda berhasil logout!'); window.location='login.php';</script>";
exit;