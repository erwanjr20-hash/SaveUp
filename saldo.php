<?php
include "../../config/koneksi.php";
session_start();

if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: login/login.php");
    exit;
}
$id_user = $_SESSION['id_user'];
$data_transaksi = $pdo->query("SELECT transaksi.id_transaksi, transaksi.keterangan, transaksi.jumlah, transaksi.tanggal, transaksi.Catatan, kategori.nama_kategori, kategori.jenis FROM transaksi JOIN kategori ON transaksi.id_kategori = kategori.id_kategori WHERE transaksi.id_user = $id_user ORDER BY transaksi.id_transaksi DESC;")->fetchAll(PDO::FETCH_ASSOC);
$total_saldo = $pdo->query("SELECT total_saldo FROM saldo WHERE id_saldo AND id_user = $id_user");
$total = $total_saldo->fetch(PDO::FETCH_ASSOC);
$total_pemasukan = $pdo->query("SELECT SUM(jumlah) AS totalMasukan FROM transaksi JOIN kategori ON transaksi.id_kategori = kategori.id_kategori WHERE kategori.jenis = 'pemasukan' AND transaksi.id_user = $id_user")->fetch(PDO::FETCH_ASSOC);
$total_pengeluaran = $pdo->query("SELECT SUM(jumlah) AS totalPengeluaran FROM transaksi JOIN kategori ON transaksi.id_kategori = kategori.id_kategori WHERE kategori.jenis = 'pengeluaran' AND transaksi.id_user = $id_user")->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
    <style>
        :root {
        --Navy-blue: #172554;
        --navy-gelap: #0F2340;
        --Blue: #2563EB;
        --White: #FFFFFF;
        --Light-Gray: #F1F5F9;
        --Secondary-Text : #64748B;
        --Dark-Text : #1E293B;
        --Green : #16A34A;
        --Red : #DC2626;
        --active : #378ADD;
        --hover : #1E4268;
        }
        .flex3 {
            display: flex;
            align-items: center;
            margin-left: 20px;
            margin-right: 20px;
        }
        .table1 {
            width: 100%;
            border-collapse: collapse;
            font-weight: bold;
        }
        .container-table1 {
            margin-left: 30px;
            margin-top: 20px;
            border: 1px solid #ddd;
            width: 95%;
            background-color: var(--White);
            border-radius: 12px;
            height: 280px;
        }
        .tk1 h2{
            font-size: 17px;
        }
        .tk1 p {
            margin-top: -10px;
            font-size: 12px;
        }
        thead {
            font-size: 12px;
        }
        .table1 tbody tr td {
            padding-top: 5px;
            padding-bottom: 5px;
            font-size: 12px;
        }
        .card-saldo {
        margin-top: 10px;
        border-radius: 12px;
        background-color: var(--White);
        border: 1px solid #ddd;
        width: 50%;
        height: 36px;
        padding: 20px;
        display: flex;
        }
        .card-saldo-besar {
        position: relative;
        margin-top: 10px;
        border-radius: 12px;
        background-color: var(--navy-gelap);
        border: 1px solid #ddd;
        width: 95%;
        height: 77px;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        overflow: hidden
        }
        .edit {
        text-decoration: none;
        color: #fff;
        background-color: #14532d;
        padding: 3px 6px;
        border-radius: 5px;
        }
        .hapus {
        text-decoration: none;
        color: #fff;
        background-color: #dc3545;
        padding: 3px 6px;
        border-radius: 5px;
        }       
        .flex-saldo {
            display: flex;
            gap: 20px;
            color: black;
        }
        .container-content-header {
            margin-left: 30px;
            margin-right: 20px; 
            max-width: 100%;
        }
        .button-transaksi1 {
            text-align: right;
            margin-right: 35px;
            margin-top: 30px;
        }
        .judul-card-saldo{
            margin-left: 10px;
        margin-top: -10px;
        font-size: 10px;
        }
        .judul-card-saldo p {
            color: var(--Secondary-Text);
        }
        .judul-card-saldo h1{
            color: var(--White);
            font-size: 25px;
        }
        .logo-saldo {
            width: 60px;
            border-radius: 6px;
            overflow: hidden;
        }
        .container-logo {
            z-index: 2;
        }
        .style1 {
        content: "";
        height: 130px;
        width: 130px;
        position: absolute;
        border: 45px solid var(--hover);
        border-radius: 50%;
        top: -105px;
        right: -30px;
        overflow: hidden;
        z-index: 1;
        }
    </style>
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
                <a href="dashboard.php">
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
                <a href="saldo.php" class="active">
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
                    <h3>Saldo</h3>
                </div>
                <div class="lambang-akun"></div>
            </div>
            <div class="section">
                
                            <div class="container-content-header">
                                <div class="card-saldo-besar">
                                    <div class="judul-card-saldo">
                                    <p>Saldo Anda Saat ini</p>
                                    <h1>Rp <?php echo number_format($total['total_saldo'] ?? 0, "0", ",", ".") ?></h1>
                                    <div class="style1"></div>
                                    </div>
                                    <div class="container-logo">
                                    <img class="logo-saldo" src="../../asset/Logo aplikasi.jpg" alt="">
                                    </div>
                                </div>
                                <div class="flex-saldo">
                                <div class="card-saldo">
                                <div class="kotak-card1">
                                    <p><b>&ShortDownArrow;</b></p>
                                </div>
                                <div class="judul-card">
                                    <p>Total Pemasukan</p>
                                    <h3>Rp <?php echo number_format($total_pemasukan['totalMasukan'] ?? 0, "0", ",", ".") ?? 0; ?></h3>
                                </div>
                                                    </div>
                                                    <div class="card-saldo">
                                <div class="kotak-card2">
                                    <p><b>&ShortUpArrow;</b></p>
                                </div>
                                <div class="judul-card">
                                    <p>Total Pengeluaran</p>
                                    <h3>Rp <?php echo number_format($total_pengeluaran['totalPengeluaran'] ?? 0, "0", ",", ".")?></h3>
                                </div>
                                                    </div>
                                                </div>
                            </div>
                    
                    <div class="container-table1">
                        <table class="table1">
                            <thead>
                                <tr>
                                    <th>tanggal</th>
                                    <th>tanggal</th>
                                    <th>keterangan</th>
                                    <th>Jenis</th>
                                    <th>saldo</th>
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
                                    <td class="kategori"><?php echo htmlspecialchars($dt['jenis'])?></td>
                                    <td class="Jumlah"><?php echo number_format($dt['jumlah'], "0", ",", ".")?></td>
                                </tr>
                                <?php 
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div>
                    </div>
            </div>
        </div>
    </div>
</body>
</html>
<?Php
if (isset($_GET['hapus'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM transaksi WHERE id_transaksi = ?");
        $stmt->execute([$_GET['hapus']]);
        echo "<script>alert('Kategori Berhasil dihapus!'); window.location='data_transaksi.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Kategori tidak bisa dihapus karena masih dipakai oleh barang.'); window.location='data_transaksi.php';</script>";
    }
    exit;
}
?>