<?php
include "../../../config/koneksi.php";
session_start();
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: ../../login/login.php");
    exit;
}
$id_user = $_SESSION['id_user'];

$stmt = $pdo->prepare("SELECT id_kategori, id_user, nama_kategori, jenis FROM kategori WHERE id_user = ? ORDER BY id_kategori DESC");
$stmt->execute([$id_user]);
$data_kategori = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_kategori = $pdo->query("SELECT COUNT(*) AS total_kategori FROM kategori WHERE id_user = $id_user");
$data = $total_kategori->fetch(PDO::FETCH_ASSOC);

$total_kategori_pemasukan = $pdo->query("SELECT COUNT(jenis) AS total_kategori_pemasukan FROM kategori WHERE id_user = $id_user AND jenis = 'pemasukan'");
$data_masuk = $total_kategori_pemasukan->fetch(PDO::FETCH_ASSOC);

$total_kategori_pengeluaran = $pdo->query("SELECT COUNT(jenis) AS total_kategori_pengeluaran FROM kategori WHERE id_user = $id_user AND jenis = 'pengeluaran'");
$data_keluar = $total_kategori_pengeluaran->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
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
        .card-kategori {
        margin-top: 10px;
        border-radius: 12px;
        background-color: var(--White);
        border: 1px solid #ddd;
        width: 270px;
        height: 37px;
        padding: 20px;
        display: flex;

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
        .flex-kategori {
            display: flex;
            justify-content: space-around;
            margin-left: 30px;
            margin-right: 30px;
            color: black;
        }
        .button-transaksi1 {
            text-align: right;
            margin-right: 35px;
            margin-top: 30px;
        }
        .judul-card-kategori{
            margin-left: 10px;
        margin-top: -10px;
        font-size: 10px;
        }
        .judul-card-kategori p {
            color: var(--Secondary-Text);
        }
        .judul-card-kategori h3{
            color: black;
        }
    </style>
</head>
<body>
    <div class="container-utama">
        <div class="sidebar">
        <div class="content-sidebar">
            <div class="card">
                <img src="../../../asset/Logo aplikasi.jpg" alt="">
                <div class="teks">
                    <h2>SaveUp</h2>
                    <p>Kelola keuanganmu</p>
                </div>
            </div>
            <div class="section-sidebar">
                <h3>MENU UTAMA</h3>
                <a href="./../dashboard.php">
                    <span>DB</span>
                    Dashboard
                </a>
                <a href="../data_transaksi/data_transaksi.php">
                    <span>DT</span>
                    Data Transaksi
                </a>
                <a href="kategori.php" class="active">
                    <span>KT</span>
                    Kategori
                </a>
                <a href="./../saldo.php">
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
                    <a href="../../login/logout.php">
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
                    <h3>Data Transaksi</h3>
                </div>
                <div class="lambang-akun"></div>
            </div>
            <div class="section">
                <div class="button-transaksi1">
                        <a class="btn" href="tambah.php">+ Tambah Kategori</a>
                    </div>  
                    <div class="flex-kategori">
                        <div class="card-kategori">
                            <div class="kotak-card">
                            <p><b>#</b></p>
                        </div>
                            <div class="judul-card-kategori">
                            <p>Total Kategori</p>
                            <h3><?php echo htmlspecialchars($data['total_kategori']);?> Kategori</h3>
                        </div>
                    </div>
                    <div class="card-kategori">
                        <div class="kotak-card1">
                            <p><b>&ShortDownArrow;</b></p>
                        </div>
                        <div class="judul-card">
                            <p>Total Pemasukan</p>
                            <h3><?php echo htmlspecialchars($data_masuk['total_kategori_pemasukan'] ?? 0)?> Kategori</h3>
                        </div>
                    </div>
                    <div class="card-kategori">
                        <div class="kotak-card2">
                            <p><b>&ShortUpArrow;</b></p>
                        </div>
                        <div class="judul-card">
                            <p>Total Pengeluaran</p>
                            <h3><?php echo htmlspecialchars($data_keluar['total_kategori_pengeluaran'] ?? 0)?> Kategori</h3>
                        </div>
                    </div>
                </div>
                    <div class="container-table1">
                        <table class="table1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>id_kategori</th>
                                    <th>Nama Kategori</th>
                                    <th>Jenis Kategori</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?Php
                                $no = 1;
                                foreach ($data_kategori as $dt) :
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?php echo htmlspecialchars($dt['id_kategori'])?></td>
                                    <td><?php echo htmlspecialchars($dt['nama_kategori'])?></td>
                                    <td class="jenis"><?php echo htmlspecialchars($dt['jenis'])?></td>
                                    <td>
                                        <a class="edit" href='edit.php?id=<?= $dt['id_kategori']; ?>'>Edit</a>
                                        <a class="hapus" href="kategori.php?hapus=<?= $dt['id_kategori']; ?>" onclick="return confirm('Hapus Barang Ini?')">Hapus</a>
                                </td>
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