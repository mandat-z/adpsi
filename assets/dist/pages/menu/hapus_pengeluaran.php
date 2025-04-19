<?php
include 'C:/xampp/htdocs/adpsi/config/config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus data berdasarkan id
    $query = "DELETE FROM tb_pengeluaran WHERE id = '$id'";

    if (mysqli_query($koneksi, $query)) {
        header('Location: pengeluaran.php?status=hapus_sukses');
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }
}
?>
