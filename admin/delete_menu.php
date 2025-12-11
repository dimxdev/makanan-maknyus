<?php
include("../Koneksi/koneksi.php"); // Pastikan file koneksi mengarah ke database dengan benar
error_reporting(0);
session_start();

// Pastikan admin sudah login
if (empty($_SESSION["adm_id"])) {
    header("location:index.php");
    exit();
}

// Pastikan parameter menu_del dikirim melalui URL
if (isset($_GET['menu_del'])) {
    // Mengambil id_makanan yang akan dihapus
    $menu_id = $_GET['menu_del'];
    
    // Menyusun query untuk menghapus data dari tabel makanan
    $query = "DELETE FROM makanan WHERE id_makanan = '$menu_id'";
    
    // Menjalankan query
    if (mysqli_query($db, $query)) {
        // Jika berhasil, arahkan kembali ke halaman all_menu.php
        header("location:all_menu.php");
        exit();
    } else {
        // Tampilkan pesan error jika query gagal
        echo "Error deleting record: " . mysqli_error($db);
    }
} else {
    // Jika parameter tidak ada, kembali ke halaman all_menu.php
    header("location:all_menu.php");
    exit();
}
?>
