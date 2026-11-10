<?php
include "../../../config/koneksi.php";
session_start();
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: ../../login/login.php");
    exit;
}
$id_user = $_SESSION['id_user'];
$stmt = $pdo->prepare("SELECT id_kategori, nama_kategori FROM kategori WHERE id_user = ? ORDER BY id_kategori DESC");
$stmt->execute([$id_user]);
$data_kategori = $stmt->fetchAll(PDO::FETCH_ASSOC);


if(isset($_POST['btn_tambah'])) {
    $tanggal = $_POST['tanggal'];
    $Keterangan = $_POST['keterangan'];
    $id_kategori = $_POST['kategori'];
    $jumlah = $_POST['jumlah'];
    $Catatan = $_POST['catatan'];

    $query = "INSERT INTO transaksi (id_user, id_kategori, keterangan, jumlah, tanggal, Catatan) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id_user,$id_kategori,$Keterangan,$jumlah,$tanggal,$Catatan]);
    $id_transaksi = $pdo->lastInsertId();
    $cek_jenis = $pdo->prepare("SELECT jenis FROM kategori WHERE id_user = ? AND id_kategori = ?");
    $cek_jenis->execute([$id_user, $id_kategori]);
    $data_cek = $cek_jenis->fetch(PDO::FETCH_ASSOC);

    if ($data_cek['jenis'] == 'pemasukan') {
        $saldo_masuk = $pdo->prepare("UPDATE saldo SET total_saldo = total_saldo + ? WHERE id_user = ?");
        $saldo_masuk->execute([$jumlah, $id_user]);

    } elseif ($data_cek['jenis'] == 'pengeluaran') {
        $saldo_keluar = $pdo->prepare("UPDATE saldo SET total_saldo = total_saldo - ? WHERE id_user = ?");
        $saldo_keluar->execute([$jumlah, $id_user]);
    }
    echo "<script>
            alert('Transaksi Berhasil!');
            window.location='data_transaksi.php';
            </script>";
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
        .flex-barang1 {
            display: flex;
            gap: 20px;
        }
        .tanggal-transaksi input {
            margin-top: 2px;
            width: 275px;
            padding: 6px;
            font-size: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            color: var(--Secondary-Text);
        }
        .tanggal-transaksi label {
            font-size: 12px;
        }
        .jenis-transaksi select {
            margin-top: 2px;
            width: 275px;
            padding: 7px;
            font-size: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            color: var(--Secondary-Text);
        }
        .jenis-transaksi label {
            font-size: 12px;
        }
        .keterangan input {
            margin-top: 2px;
            width: 95%;
            padding: 7px;
            font-size: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            color: var(--Secondary-Text);
        }
        .keterangan label {
            font-size: 12px;
        }
        .kategori select {
            margin-top: 2px;
            width: 290px;
            padding: 6px;
            font-size: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            color: var(--Secondary-Text);
        }
        .kategori label {
            font-size: 12px;
        }
        .jumlah input {
            padding: 7px;
            font-size: 10px;
            border: none;
            border-left: 1px solid #ddd;
            color: var(--Secondary-Text);
            width: 235px;
        }
        .jumlah label{
            font-size: 12px;
        }
        .Rp {
            margin-top: 2px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 10px;
            width: 275px;
            padding-left: 5px;
            padding-right: -5px;
            background-color: var(--active);
        }
        .Catatan textarea{
        height: 100px;
        width: 96%;
        }
        .Catatan label {
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
        .btn-tambah {
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
                            <div class="tanggal-transaksi">
                                <label for="Tanggal">Tanggal Transaksi</label><br>
                                <input type="date" name="tanggal" id="Tanggal" value="<?= date('Y-m-d') ?>" required>
                            </div>
                            </div>
                            <div class="keterangan">
                                    <label for="keterangan">keterangan</label><br>
                                    <input type="text" name="keterangan" id="keterangan" required>
                                </div>
                            <div class="flex-barang1">
                                <div class="kategori">
                                    <label for="Kategori">Kategori</label><br>
                                    <select name="kategori" id="Kategori" required>
                                        <option value="" disabled selected> Pilih Kategori </option>
                                        <?php foreach($data_kategori as $d) : ?>
                                        <option value="<?= $d['id_kategori']; ?>"><?= $d['nama_kategori']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="jumlah">
                                    <label for="Jumlah">Jumlah</label><br>
                                    <div class="Rp">Rp <input type="number" name="jumlah" id="Jumlah" required></div>
                                </div>
                            </div>
                            <div class="Catatan">
                                <label for="Catatan">Catatan</label><br>
                                <textarea name="catatan" id="Catatan" required></textarea>
                            </div>
                        <hr class="hr2">
                        <div class="double-button">
                            <a class="batal" href="data_transaksi.php"><b>Batal</b></a>
                            <button name="btn_tambah" class="btn-tambah"><b>Simpan Transaksi</b></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>