<?php
include("../Koneksi/koneksi.php"); // Pastikan file koneksi mengarah ke database yang benar
error_reporting(0);
session_start();

// Cek apakah admin sudah login
if (empty($_SESSION["adm_id"])) {
    header("Location: index.php");
    exit();
}

// Cek apakah parameter 'order_del' ada di URL
if (isset($_GET['order_del'])) {
    // Mengambil ID order yang akan dihapus dan validasi sebagai integer
    $order_id = $_GET['order_del'];

    if (!filter_var($order_id, FILTER_VALIDATE_INT)) {
        echo "ID order tidak valid.";
        exit();
    }

    // Mulai transaksi
    mysqli_begin_transaction($db);

    try {
        // 1. Hapus data dari tabel 'order_item'
        $stmt1 = $db->prepare("DELETE FROM order_item WHERE id_order = ?");
        if ($stmt1 === false) {
            throw new Exception("Prepare statement failed: " . $db->error);
        }
        $stmt1->bind_param("i", $order_id);
        if (!$stmt1->execute()) {
            throw new Exception("Execute statement failed: " . $stmt1->error);
        }
        $stmt1->close();

        // 2. Hapus data dari tabel 'delivery'
        $stmt2 = $db->prepare("DELETE FROM delivery WHERE id_order = ?");
        if ($stmt2 === false) {
            throw new Exception("Prepare statement failed: " . $db->error);
        }
        $stmt2->bind_param("i", $order_id);
        if (!$stmt2->execute()) {
            throw new Exception("Execute statement failed: " . $stmt2->error);
        }
        $stmt2->close();

        // 3. Hapus data dari tabel 'orders'
        $stmt3 = $db->prepare("DELETE FROM orders WHERE id_order = ?");
        if ($stmt3 === false) {
            throw new Exception("Prepare statement failed: " . $db->error);
        }
        $stmt3->bind_param("i", $order_id);
        if (!$stmt3->execute()) {
            throw new Exception("Execute statement failed: " . $stmt3->error);
        }
        $stmt3->close();

        // Commit transaksi
        mysqli_commit($db);

        // Arahkan kembali ke halaman all_orders.php dengan pesan sukses
        header("Location: all_orders.php?msg=Order berhasil dihapus");
        exit();
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        mysqli_rollback($db);
        echo "Error saat menghapus order: " . $e->getMessage();
    }
} else {
    echo "ID order tidak ditemukan untuk dihapus.";
}
?>
