<?php
include "../../../config/koneksi.php";

if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: ../../login/login.php");
    exit;
}
$id_user = $_SESSION['id_user'];

$data_kategori = "SELECT nama_kategori, jenis FROM kategori WHERE id_user = ?";
$data = $pdo->prepare($data_kategori);
$data->execute([$id_user]);
$isi_data = $data->fetch(PDO::FETCH_ASSOC);


if(isset($_POST['btn_tambah'])) {
    $id_kategori = $_POST['id_kategori'];
    $nama_kategori = $_POST['kategori'];
    $jenis = $_POST['level'];

    $query = "UPDATE kategori SET nama_kategori = ?, jenis = ? WHERE id_kategori = ? AND id_user = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$nama_kategori, $jenis, $id_kategori, $id_user]);
}
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

        .container-tambah {
            margin-left: 200px;
            margin-top: 5px;
            border: 1px solid #ddd;
            width: 600px;
            background-color: #fff;
            padding-left: 10px;
            padding-top: 2px;
            border-radius: 10px;
        }
        .data {
        display: inline-block;
        margin-top: 30px;
        margin-left: 200px;
        font-size: 12px;
        text-decoration: none;
        color: #64748B;
        }
        .hr2 {
            margin-top: 10px;
            width: 95%;
            margin-left: 5px;
        }
        .full {
            width: 103%;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .teks-tambah p{
        margin-top: -10px;
        font-size: 12px;
        color: var(--Secondary-Text);
        }
        .teks-tambah h3 {
            font-size: 15px;
        }
        .keterangan input {
            margin-top: 2px;
            width: 95%;
            padding: 7px;
            font-size: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            color: var(--Secondary-Text);
            margin-bottom: 10px;
        }
        .keterangan label {
            font-size: 12px;
        }
        .kategori select {
            margin-top: 2px;
            width: 98%;
            padding: 6px;
            font-size: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            color: var(--Secondary-Text);
            margin-bottom: 10px;
        }
        .kategori label {
            font-size: 12px;
        }
        .double-button {
        display: flex;
        justify-content: right;
        gap: 20px;
        margin: 20px;
        }
        .batal {
            text-decoration: none;
            padding: 5px 10px;
            display: inline-block;
            font-size: 10px;
            color: var(--Secondary-Text);
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .btn-edit {
        border: 1px solid #ddd;
        font-size: 10px;
        background-color: var(--Blue);
        padding: 5px 10px;
        color: var(--White);
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
                <a href="./../dashboard.php">
                    <span>DB</span>
                    Dashboard
                </a>
                <a href="../data_transaksi/data_transaksi.php">
                    <span>DT</span>
                    Data Transaksi
                </a>
                <a href="../kategori/kategori.php" class="active">
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
                    <h3>Tambah Transaksi</h3>
                </div>
                <div class="lambang-akun"></div>
            </div>
            <div class="section">
                <a class="data" href="data_transaksi.php"><b>&ShortLeftArrow; Kembali ke Data Transaksi</b></a>
                <div class="container-tambah">
                    <div class="teks-tambah">
                        <h3>Informasi Transaksi</h3>
                        <p>Lengkapi Detail Transaksi dengan benar.</p>
                    </div>
                        <hr class="full">
                    <form method="post">
                        <div class="flex-barang1">
                            </div>
                            <div class="keterangan">
                                    <label for="nama-kategori"><b>Nama Kategori</b></label><br>
                                    <input type="text" name="kategori" id="nama-kategori" placeholder="Masukkan nama Kategori" value="<?= htmlspecialchars($isi_data['nama_kategori']) ?>" required>
                                </div>
                                <div class="kategori">
                                    <label for="Kategori"><b>Kategori</b></label><br>
                                    <select name="level" id="Kategori" required>
                                        <option value="" disabled selected><?= htmlspecialchars($isi_data['jenis']); ?></option>
                                        <option value="pemasukan">pemasukan</option>    
                                        <option value="pengeluaran">pengeluaran</option>
                                    </select>
                                </div>
                        <hr class="hr2">
                        <div class="double-button">
                            <a class="batal" href="kategori.php"><b>Batal</b></a>
                            <button name="btn_tambah" class="btn-edit"><b>Simpan Transaksi</b></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>