<?php 
session_start();
include 'config.php';

$username = $_POST['username'];
$password = $_POST['password']; // This is already hashed by the client

// Cek user berdasarkan username
$query = mysqli_query($koneksi, "SELECT * FROM tb_users WHERE username='$username'");
$user = mysqli_fetch_assoc($query);

if ($user && hash_equals($user['password'], $password)) { // Compare hashed passwords
    // Simpan session user
    $_SESSION['username'] = $user['username'];

    header("Location: ../assets");
    exit;
} else {
    header("Location: ../index.php?error=1");
    exit;
}
?>
