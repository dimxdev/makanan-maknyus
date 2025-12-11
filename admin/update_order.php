<?php
include("../Koneksi/koneksi.php");
error_reporting(0);

// Cek apakah data telah dikirimkan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $order_id = $_POST['order_id'];
    $delivery_status = $_POST['delivery_status'];

    // Query untuk memperbarui status pengiriman
    $query = "UPDATE delivery SET status = '$delivery_status' WHERE id_order = '$order_id'";

    if (mysqli_query($db, $query)) {
        // Jika berhasil, arahkan kembali ke halaman detail order atau halaman lain
        header("location: all_orders.php");
        exit();
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error: " . mysqli_error($db);
    }
}
?>
