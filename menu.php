<?php
include("Koneksi/koneksi.php");
session_start();

// Query untuk mendapatkan data makanan
$sql = "SELECT id_makanan, nama_makanan, deskripsi, harga, img FROM Makanan";
$result = $db->query($sql);

if (!$result) {
    die("Error dalam menjalankan query: " . $db->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="css/header.css" rel="stylesheet">
    <link href="css/menu.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="menu-page" data-logged-in="<?php echo isset($_SESSION['id_user']) ? 'true' : 'false'; ?>">
    <header id="header" class="header-scroll top-header headrom">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <img class="img-rounded" src="images/logo.png" alt="Logo" />
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbarCollapse" aria-controls="mainNavbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNavbarCollapse">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="menu.php">Menu</a></li>
                        <?php if (empty($_SESSION["id_user"])): ?>
                            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                            <li class="nav-item"><a class="nav-link" href="registration.php">Register</a></li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link" href="orderan.php">My Orders</a></li>
                            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a id="shopping-cart-button" class="nav-link">
                                <i data-feather="shopping-cart"></i>
                                <span id="cart-badge" class="badge">0</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <div class="card-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card" data-id="<?php echo htmlspecialchars($row['id_makanan']); ?>">
                    <img src="images/makanan/<?php echo htmlspecialchars($row['img']); ?>" class="product-image" alt="<?php echo htmlspecialchars($row['nama_makanan']); ?>">
                    <h2 class="product-title"><?php echo htmlspecialchars($row['nama_makanan']); ?></h2>
                    <p class="product-description"><?php echo htmlspecialchars($row['deskripsi']); ?></p>
                    <p class="product-price">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                    <!-- Menambahkan id_makanan ke dalam parameter fungsi addToCart -->
                    <button class="buy-button" onclick="addToCart('<?php echo $row['id_makanan']; ?>', '<?php echo htmlspecialchars($row['nama_makanan']); ?>', <?php echo $row['harga']; ?>, 'images/makanan/<?php echo htmlspecialchars($row['img']); ?>')">Tambahkan Keranjang</button>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Tidak ada data makanan tersedia.</p>
        <?php endif; ?>
    </div>
    <div id="cart-tab" class="cart-tab">
        <button id="close-cart-btn"><i class="fa fa-close"></i></button>
        <ul id="cart-items"></ul>
        <p class="cart-total">Total: Rp 0</p>
        <button id="checkout-btn">Checkout</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="js/menu.js"></script>
    <script>feather.replace();</script>
</body>
</html>
