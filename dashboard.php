<?php
include "../../config/koneksi.php";
session_start();
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: ./login/login.php");
    exit;
}
$id_user = $_SESSION['id_user'];

$data_transaksi = $pdo->query("SELECT transaksi.id_transaksi, transaksi.keterangan, transaksi.jumlah, transaksi.tanggal, transaksi.Catatan, kategori.nama_kategori, kategori.jenis FROM transaksi JOIN kategori ON transaksi.id_kategori = kategori.id_kategori WHERE transaksi.id_user = $id_user ORDER BY transaksi.id_transaksi DESC;")->fetchAll(PDO::FETCH_ASSOC);
$berapa_transaksi = $pdo->query("SELECT COUNT(*) AS transaksi FROM transaksi WHERE id_user = $id_user");
$data = $berapa_transaksi->fetch(PDO::FETCH_ASSOC);
$total_pemasukan = $pdo->query("SELECT SUM(jumlah) AS totalMasukan FROM transaksi JOIN kategori ON transaksi.id_kategori = kategori.id_kategori WHERE kategori.jenis = 'pemasukan' AND transaksi.id_user = $id_user")->fetch(PDO::FETCH_ASSOC);
$total_pengeluaran = $pdo->query("SELECT SUM(jumlah) AS totalPengeluaran FROM transaksi JOIN kategori ON transaksi.id_kategori = kategori.id_kategori WHERE kategori.jenis = 'pengeluaran' AND transaksi.id_user = $id_user")->fetch(PDO::FETCH_ASSOC);
$total_saldo = $pdo->query("SELECT total_saldo FROM saldo WHERE id_saldo AND id_user = $id_user");
$total = $total_saldo->fetch(PDO::FETCH_ASSOC);
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
    <div class="container-utama">
        <div class="sidebar">
        <div class="content-sidebar">
            <div class="card">
                <img src="../../asset/Logo aplikasi.jpg" alt="">
                <div class="teks">
                    <h2>SaveUp</h2>
                    <p>Kelola keuanganmu</p>
                </div>
            </div>
            
            <div class="section-sidebar">
                <h3>MENU UTAMA</h3>
                <a href="dashboard.php" class="active">
                    <span>DB</span>
                    Dashboard
                </a>
                <a href="./data_transaksi/data_transaksi.php">
                    <span>DT</span>
                    Data Transaksi
                </a>
                <a href="./kategori/kategori.php">
                    <span>KT</span>
                    Kategori
                </a>
                <a href="saldo.php">
                    <span>SL</span>
                    Saldo
                </a>
            </div>
            <hr>
            <div class="footer">
                <div class="akun">
                    <div class="lambang-akun"></div>
                    <div class="nama">
                        <p><?php echo htmlspecialchars($_SESSION['username']);?></p>
                    </div>
                </div>
                <div class="log-out">
                    <a href="../login/logout.php">
                    <span>&rightarrow;</span>
                    Logout
                </a>
                </div>
            </div>
        </div>
        </div>
        <div class="Content">
            <div class="header">
                <div class="sub-header">
                    <h3>Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?></h3>
                </div>
                <div class="lambang-akun"></div>
            </div>
            <div class="section">
                <div class="flex1">
                    <div class="card1">
                        <div class="kotak-card">
                            <p><b>Rp</b></p>
                        </div>
                        <div class="judul-card1">
                            <p>Total Saldo</p>
                            <h3>Rp <?php echo number_format($total['total_saldo'] ?? 0, "0", ",", ".")?></h3>
                        </div>
                    </div>
                    <div class="card2">
                        <div class="kotak-card1">
                            <p><b>&ShortDownArrow;</b></p>
                        </div>
                        <div class="judul-card">
                            <p>Total Pemasukan</p>
                            <h3>Rp <?php echo number_format($total_pemasukan['totalMasukan'] ?? 0, "0", ",", ".") ?? 0; ?></h3>
                        </div>
                    </div>
                    <div class="card2">
                        <div class="kotak-card2">
                            <p><b>&ShortUpArrow;</b></p>
                        </div>
                        <div class="judul-card">
                            <p>Total Pengeluaran</p>
                            <h3>Rp <?php echo number_format($total_pengeluaran['totalPengeluaran'] ?? 0, "0", ",", ".")?></h3>
                        </div>
                    </div>
                    <div class="card2">
                        <div class="kotak-card1">
                            <p><b>#</b></p>
                        </div>
                        <div class="judul-card">
                            <p>Jumlah Transaksi</p>
                            <h3><?php echo htmlspecialchars($data['transaksi'] ?? 0); ?> Transaksi</h3>
                        </div>
                    </div>
                </div>
                <div class="flex2">
                    <div class="container-table">
                        <div class="judul-table">
                            <h3>Transaksi Terbaru</h3>
                            <p><a href="./data_transaksi/data_transaksi.php"><b>Lihat semua &ShortRightArrow;</b></a></p>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Kategori</th>
                                    <th>Jenis Transaksi</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?Php
                                $no = 1;
                                foreach ($data_transaksi as $dt) :
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?php echo htmlspecialchars($dt['tanggal'])?></td>
                                    <td class="keterangan"><?php echo htmlspecialchars($dt['keterangan'])?></td>
                                    <td class="kategori"><?php echo htmlspecialchars($dt['nama_kategori'])?></td>
                                    <td class="jenis"><?php echo htmlspecialchars($dt['jenis'])?></td>
                                    <td class="Jumlah"><?php echo number_format($dt['jumlah'], "0", ",", ".")?></td>
                                </tr>
                                <?php 
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div>
                    <div class="button-transaksi">
                        <a class="btn" href="./data_transaksi/tambah.php">+ Tambah Transaksi</a>
                    </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>