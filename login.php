<?php
include '../../config/koneksi.php';
session_start();
if(isset($_SESSION['status_login']) && $_SESSION['status_login'] === true) {
    header("Location: ../Halaman/dashboard.php");
    exit;
}

if(isset($_POST['btn'])) {
    $nama = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id_user, Nama, `password` FROM users WHERE Nama = ? AND `password` = ?");
    $stmt->execute([$nama, $password]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($data) {
            $_SESSION['status_login'] = true;
            $_SESSION['username'] = $data['Nama'];
            $_SESSION['id_user'] = $data['id_user'];

            echo "<script>
            alert('Login Berhasil!');
            window.location='../Halaman/dashboard.php';
            </script>";
exit;
    } else {
        echo "<script>alert('Username Atau Password Salah'); window.location='login.php';</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <div class="bg1">
        <div class="header-bg1">
            <img src="../../asset/Logo Aplikasi.jpg" alt="">
            <div class="judul">
                <h2>SaveUp</h2>
                <p>Kelola keuanganmu</p>
            </div>
        </div>
        <div class="section1">
                <p><b>KEUANGAN LEBIH TERARAH.</b></p>
                <h2>Mulai kebiasaan finansial <br> yang lebih baik hari ini.</h2>
                <h3>Catat pemasukan, pantau pengeluaran, dan kelola saldo dalam satu <br>tempat yang sederhana.</h3>
            </div>
        <div class="footer">
            <h2>© 2026 FinTrack. Aplikasi Keuangan Pribadi.</h2>
        </div>
        <div class="style1"></div>
        <div class="style2"></div>
        </div>
        <div class="bg2">
            <div class="container2">
                <div class="header">
                    <p><b>SELAMAT DATANG KEMBALI</b></p>
                    <div class="sub-head">
                        <H2>Masuk ke SaveUp</H2>
                        <p><b>Masukkan informasi akun Anda untuk melanjutkan.</b></p>
                </div>
                </div>
            </div>
            <div class="content">
                    <form method="post">
                        <div class="user">
                            <label for=""><b>Username</b></label><br>
                            <input type="text" name="username" id="" placeholder="Masukkan username">
                        </div>
                        <div class="pass">
                            <label for=""><b>Password</b></label><br>
                            <input type="password" name="password" id="" placeholder="Masukkan password"> 
                        </div>
                        <div class="btn">
                            <button name="btn"><b>Login</b></button>
                        </div>
                    </form>
                </div>
                <hr class="hr">
                <div class="footer-bg2">
                        <p><b>Belum Memiliki Akun? <a href="register.php">Register Sekarang</a></b></p>
                </div>
        </div>
    </div>
</body>
</html>