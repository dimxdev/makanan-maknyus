<?php
session_start();

// Pastikan file koneksi sudah sesuai dengan database yang ada
include("Koneksi/koneksi.php");

// Periksa apakah pengguna sudah login
if (empty($_SESSION['id_user'])) {
    header('location:login.php');
    exit();
}

// Periksa apakah parameter order_del ada dalam URL
if (isset($_GET['order_del'])) {
    $id_order = intval($_GET['order_del']); // Validasi input untuk keamanan

    // Query untuk menghapus data dari tabel delivery
    $delete_delivery = "DELETE FROM delivery WHERE id_order = $id_order";
    mysqli_query($db, $delete_delivery);

    // Query untuk menghapus data dari tabel order_item
    $delete_order_item = "DELETE FROM order_item WHERE id_order = $id_order";
    mysqli_query($db, $delete_order_item);

    // Query untuk menghapus data dari tabel orders
    $delete_order = "DELETE FROM orders WHERE id_order = $id_order";
    $result = mysqli_query($db, $delete_order);

    if ($result) {
        // Redirect kembali ke halaman orderan setelah berhasil menghapus
        $_SESSION['success'] = "Order berhasil dihapus.";
        header('location:orderan.php');
        exit();
    } else {
        // Jika terjadi kesalahan
        $_SESSION['error'] = "Terjadi kesalahan saat menghapus order.";
        header('location:orderan.php');
        exit();
    }
} else {
    // Jika parameter order_del tidak ditemukan, redirect ke halaman orderan
    header('location:orderan.php');
    exit();
}
?>
