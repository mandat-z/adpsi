<?php
include 'C:/xampp/htdocs/adpsi/config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];
    $kategori = $_POST['kategori'];
    $nominal = $_POST['nominal'];

    $query = "INSERT INTO tb_pengeluaran (tanggal, keterangan, kategori, nominal) 
              VALUES ('$tanggal', '$keterangan', '$kategori', '$nominal')";

    if (mysqli_query($koneksi, $query)) {
        header('Location: pengeluaran.php?status=sukses');
    } else {
        echo "Gagal menyimpan data: " . mysqli_error($koneksi);
    }
}
?>
