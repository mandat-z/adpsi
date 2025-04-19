<?php
include 'C:/xampp/htdocs/adpsi/config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];
    $kategori = $_POST['kategori'];
    $nominal = $_POST['nominal'];

    $query = "UPDATE tb_pengeluaran 
              SET tanggal = '$tanggal', keterangan = '$keterangan', kategori = '$kategori', nominal = '$nominal' 
              WHERE id = '$id'";

    if (mysqli_query($koneksi, $query)) {
        header('Location: pengeluaran.php?status=sukses');
    } else {
        echo "Gagal memperbarui data: " . mysqli_error($koneksi);
    }
}
?>
