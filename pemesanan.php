<?php
include("Koneksi/koneksi.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

// Ambil ID pengguna dari session
$userId = $_SESSION['id_user'];

// Ambil data pengguna dari database
$query = "SELECT * FROM users WHERE id_user = ?";
$stmt = $db->prepare($query);
if (!$stmt) {
    die("Error preparing statement: " . $db->error);
}

$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
if (!$result || $result->num_rows === 0) {
    die("Pengguna tidak ditemukan atau query error: " . $db->error);
}

$user = $result->fetch_assoc();

// Proses form jika ada data POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi input
    $paymentMethod = htmlspecialchars($_POST['paymentMethod'] ?? '');
    $shippingMethod = htmlspecialchars($_POST['shippingMethod'] ?? '');
    $cartItems = json_decode($_POST['cartItems'] ?? '[]', true);

    // Validasi nilai paymentMethod
    $allowedPaymentMethods = ['transfer', 'tunai'];
    if (!in_array($paymentMethod, $allowedPaymentMethods)) {
        die("Metode pembayaran tidak valid.");
    }

    if (empty($cartItems) || empty($paymentMethod) || empty($shippingMethod)) {
        die("Data tidak lengkap. Mohon lengkapi semua input.");
    }

    // Validasi shipping method
    $shippingQuery = "SELECT id_jasaantar FROM jasaantar WHERE id_jasaantar = ?";
    $shippingStmt = $db->prepare($shippingQuery);
    if (!$shippingStmt) {
        die("Error preparing shipping statement: " . $db->error);
    }

    $shippingStmt->bind_param("i", $shippingMethod);
    $shippingStmt->execute();
    $shippingResult = $shippingStmt->get_result();

    if ($shippingResult->num_rows === 0) {
        die("Metode pengiriman tidak valid.");
    }

    // Hitung total harga dari cartItems
    $totalHarga = 0;
    foreach ($cartItems as $item) {
        if (empty($item['id']) || empty($item['price']) || empty($item['quantity'])) {
            die("Data item keranjang tidak valid. Pastikan semua item memiliki ID, harga, dan jumlah.");
        }
        $totalHarga += $item['price'] * $item['quantity'];
    }

    // Insert data ke tabel orders
    $adminId = 1; // Admin default, bisa disesuaikan sesuai kebutuhan
    $orderQuery = "INSERT INTO orders (adm_id, id_user, total_harga, MetodePembayaran) VALUES (?, ?, ?, ?)";
    $orderStmt = $db->prepare($orderQuery);
    if (!$orderStmt) {
        die("Error preparing order statement: " . $db->error);
    }

    $orderStmt->bind_param("iids", $adminId, $userId, $totalHarga, $paymentMethod);
    if (!$orderStmt->execute()) {
        die("Error executing order statement: " . $db->error);
    }
    $orderId = $db->insert_id;

    // Insert data ke tabel order_item
    $orderItemQuery = "INSERT INTO order_item (id_makanan, id_order, jumlah) VALUES (?, ?, ?)";
    $orderItemStmt = $db->prepare($orderItemQuery);
    if (!$orderItemStmt) {
        die("Error preparing order_item statement: " . $db->error);
    }

    foreach ($cartItems as $item) {
        $orderItemStmt->bind_param("iii", $item['id'], $orderId, $item['quantity']);
        if (!$orderItemStmt->execute()) {
            die("Error executing order_item statement: " . $db->error);
        }
    }

    // Insert data ke tabel delivery
    $deliveryQuery = "INSERT INTO delivery (status, id_order, id_jasaantar) VALUES (?, ?, ?)";
    $deliveryStmt = $db->prepare($deliveryQuery);
    if (!$deliveryStmt) {
        die("Error preparing delivery statement: " . $db->error);
    }

    $status = "on the way"; // Status default
    $deliveryStmt->bind_param("sii", $status, $orderId, $shippingMethod);
    if (!$deliveryStmt->execute()) {
        die("Error executing delivery statement: " . $db->error);
    }

    echo "<script>
        localStorage.removeItem('cart'); // Hapus data keranjang dari localStorage
        window.location.href='orderan.php'; // Arahkan pengguna ke halaman daftar pesanan
    </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="css/pemesanan.css">
</head>
<body>
    <div class="container">
        <h2>Checkout</h2>
        <!-- Ringkasan Belanja -->
        <div class="section">
            <h3>Ringkasan Belanja</h3>
            <ul id="cart-items"></ul>
        </div>
        <!-- Form untuk checkout -->
        <form id="checkout-form" action="" method="POST">
            <!-- Metode Pembayaran -->
            <div class="section">
                <h3>Pembayaran</h3>
                <div class="payment-options">
                <div class="option" id="transfer" onclick="selectPayment('transfer')">QRIS</div>
                <div class="option" id="tunai" onclick="selectPayment('tunai')">COD</div>
            </div>

                <input type="hidden" name="paymentMethod" id="paymentMethod">
            </div>
            <!-- Metode Pengiriman -->
            <div class="section">
                <h3>Jasa Antar</h3>
                <div class="dropdown-container">
                    <select id="shipping-method" name="shippingMethod" class="dropdown" required onchange="updateShippingCost()">
                        <option value="" disabled selected>Pilih Jasa Antar</option>
                        <?php
                        $jasaQuery = "SELECT * FROM jasaantar";
                        $jasaResult = $db->query($jasaQuery);
                        while ($row = $jasaResult->fetch_assoc()) {
                            echo "<option value='" . $row['id_jasaantar'] . "'>" . htmlspecialchars($row['nama_jasa']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <p id="shipping-cost">Biaya Pengiriman: Free</p>
            </div>
            <!-- Ringkasan Total & Tombol Checkout -->
            <div class="section">
                <table style="width: 100%; margin-bottom: 20px;">
                    <tr>
                        <td style="text-align: left; font-weight: bold; font-size: 18px;">Total Pembayaran</td>
                        <td style="text-align: right; font-weight: bold; font-size: 18px;">Rp <span id="final-total">0</span></td>
                    </tr>
                </table>
                <input type="hidden" name="cartItems" id="cartItemsData">
                <button type="button" onclick="processCheckout()">Bayar Sekarang</button>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const cartItemsContainer = document.getElementById('cart-items');
            const finalTotalContainer = document.getElementById('final-total');

            function updateCart() {
                cartItemsContainer.innerHTML = "";
                let total = 0;

                if (cart.length === 0) {
                    cartItemsContainer.innerHTML = '<li>Keranjang kosong!</li>';
                } else {
                    cart.forEach(item => {
                        const listItem = document.createElement('li');
                        listItem.innerHTML = `
                            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                                <img src="${item.image || 'https://via.placeholder.com/150'}" alt="${item.name}" 
                                     style="width: 100px; height: 100px; margin-right: 20px;">
                                <div>
                                    <p><strong>${item.name}</strong></p>
                                    <p>Rp ${item.price.toLocaleString()} x ${item.quantity}</p>
                                </div>
                            </div>
                        `;
                        cartItemsContainer.appendChild(listItem);
                        total += item.price * item.quantity;
                    });
                }

                localStorage.setItem('total', total);
                finalTotalContainer.textContent = total.toLocaleString();
            }

            updateCart();
        });

        function selectPayment(method) {
            const options = document.querySelectorAll('.payment-options .option');
            options.forEach(option => option.classList.remove('selected'));
            const selectedOption = document.getElementById(method);
            selectedOption.classList.add('selected');
            document.getElementById('paymentMethod').value = method;
        }

        function processCheckout() {
            const confirmation = confirm("Apakah Anda yakin ingin melakukan pembayaran?");
            if (confirmation) {
                // Submit form jika pengguna mengonfirmasi
                const form = document.getElementById('checkout-form');
                const cart = JSON.parse(localStorage.getItem('cart')) || [];
                document.getElementById('cartItemsData').value = JSON.stringify(cart);
                form.submit();
            }
        }
    </script>
</body>
</html>
