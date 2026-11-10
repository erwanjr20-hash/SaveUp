<?php
include "../../../config/koneksi.php";
session_start();
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: ../../login/login.php");
    exit;
}
$id_user = $_SESSION['id_user'];
$data_transaksi = $pdo->query("SELECT transaksi.id_transaksi, transaksi.keterangan, transaksi.jumlah, transaksi.tanggal, transaksi.Catatan, kategori.nama_kategori, kategori.jenis FROM transaksi JOIN kategori ON transaksi.id_kategori = kategori.id_kategori WHERE transaksi.id_user = $id_user ORDER BY transaksi.id_transaksi DESC;")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Document</title>
    <style>
        .flex3 {
            display: flex;
            align-items: center;
            justify-content: space-between;
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
                <a href="../dashboard.php">
                    <span>DB</span>
                    Dashboard
                </a>
                <a href="data_transaksi.php" class="active">
                    <span>DT</span>
                    Data Transaksi
                </a>
                <a href="../kategori/kategori.php">
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
                <div class="flex3">
                    <div class="tk1">
                    <h2>Daftar Transaksi</h2>
                    <p>kelola seluruh pemasukan dan pengurangan</p>
                </div>
                <div class="button-transaksi1">
                        <a class="btn" href="tambah.php">+ Tambah Transaksi</a>
                    </div>  
                    </div>
                    <div class="container-table1">
                        <table class="table1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Kategori</th>
                                    <th>Jenis Transaksi</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
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
                                    <td>
                                        <a class="hapus" href="data_transaksi.php?hapus=<?= $dt['id_transaksi']; ?>" onclick="return confirm('Hapus Barang Ini?')">Hapus</a>
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
        $id = $_GET['hapus'];

        // Ambil jumlah dan jenis transaksi
        $cek = $pdo->prepare("
            SELECT transaksi.jumlah, kategori.jenis
            FROM transaksi
            JOIN kategori ON transaksi.id_kategori = kategori.id_kategori
            WHERE transaksi.id_transaksi = ? AND transaksi.id_user = ?
        ");
        $cek->execute([$id, $id_user]);
        $data = $cek->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            if ($data['jenis'] == 'pemasukan') {
                $saldo = $pdo->prepare("UPDATE saldo SET total_saldo = total_saldo - ? WHERE id_user = ?");
            } else {
                $saldo = $pdo->prepare("UPDATE saldo SET total_saldo = total_saldo + ? WHERE id_user = ?");
            }

            $saldo->execute([$data['jumlah'], $id_user]);

            $stmt = $pdo->prepare("DELETE FROM transaksi WHERE id_transaksi = ? AND id_user = ?");
            $stmt->execute([$id, $id_user]);
        }

        echo "<script>alert('Transaksi berhasil dihapus!'); window.location='data_transaksi.php';</script>";

    } catch (PDOException $e) {
        echo "<script>alert('Gagal menghapus transaksi!'); window.location='data_transaksi.php';</script>";
    }
    exit;
}
?>