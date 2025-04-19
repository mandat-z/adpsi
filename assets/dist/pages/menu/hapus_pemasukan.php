<?php
include 'C:/xampp/htdocs/adpsi/config/config.php';

$id = $_GET['id']; // Mengambil ID dari URL

// Query untuk menghapus data berdasarkan ID
$query = "DELETE FROM tb_pemasukan WHERE id = $id";

if (mysqli_query($koneksi, $query)) {
    header("Location: pemasukan.php?status=hapus-sukses"); // Redirect setelah berhasil menghapus
    exit;
} else {
    echo "Gagal menghapus data: " . mysqli_error($koneksi);
}
?>
