<?php
include("../Koneksi/koneksi.php");
error_reporting(0);
session_start();

// Validasi input
if (isset($_GET['user_del']) && is_numeric($_GET['user_del'])) {
    $user_id = intval($_GET['user_del']);
    
    // Siapkan query menggunakan prepared statement
    $stmt = $db->prepare("DELETE FROM users WHERE id_user = ?");
    $stmt->bind_param("i", $user_id);
    
    // Eksekusi query
    if ($stmt->execute()) {
        // Redirect ke halaman all_users.php jika berhasil
        header("Location: all_users.php");
        exit;
    } else {
        // Tampilkan pesan error jika query gagal
        echo "Gagal menghapus pengguna.";
    }

    // Tutup statement
    $stmt->close();
} else {
    // Jika parameter tidak valid, redirect ke halaman lain atau tampilkan pesan error
    echo "ID pengguna tidak valid.";
}
$db->close();
?>
