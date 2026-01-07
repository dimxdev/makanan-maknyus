<?php
include("Koneksi/koneksi.php");
session_start();

// Query untuk mendapatkan data makanan
$query = "SELECT id_makanan, nama_makanan, deskripsi, harga, img FROM makanan LIMIT 6";
$result = $db->query($query);

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
    <title>Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
    <link href="css/header.css" rel="stylesheet">
    <link href="css/footer.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body class="home">
<header id="header" class="header-scroll top-header headrom">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img class="img-rounded" src="images/logo.png" alt="Logo">
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
                </ul>
            </div>
        </div>
    </nav>
</header>


<section class="hero">

   <div class="swiper hero-slider">

      <div class="swiper-wrapper">

         <div class="swiper-slide slide">
            <div class="content">
               <span>order online</span>
               <h3>lumpia semarang</h3>
               <a href="menu.php" class="btn">lihat menu</a>
            </div>
            <div class="image">
               <img src="images/makanan/Lumpia.jpg" alt="">
            </div>
         </div>

         <div class="swiper-slide slide">
            <div class="content">
               <span>order online</span>
               <h3>Gulai kambing</h3>
               <a href="menu.php" class="btn">lihat menu</a>
            </div>
            <div class="image">
               <img src="images/makanan/GulaiKambing.jpeg" alt="">
            </div>
         </div>

         <div class="swiper-slide slide">
            <div class="content">
               <span>order online</span>
               <h3>nasi padang</h3>
               <a href="menu.php" class="btn">lihat menu</a>
            </div>
            <div class="image">
               <img src="images/makanan/NasiPadang.jpg" alt="">
            </div>
         </div>

      </div>

      <div class="swiper-pagination"></div>

   </div>

</section>
<section class="products">

   <h1 class="title">Menu Rekomendasi</h1>

   <div class="box-container">

      <?php
      if ($result->num_rows > 0) {
          while ($makanan = $result->fetch_assoc()) {
      ?>
      <form action="" method="post" class="box">
         <a href="menu.php?pid=<?= $makanan['id_makanan']; ?>" class="fas fa-eye"></a>
         <img src="images/makanan/<?= htmlspecialchars($makanan['img']); ?>" alt="<?= htmlspecialchars($makanan['nama_makanan']); ?>">
         <div class="name"><?= htmlspecialchars($makanan['nama_makanan']); ?></div>
         <p class="description"><?= htmlspecialchars($makanan['deskripsi']); ?></p>
            <div class="flex">
            <div class="price"><span>Rp</span><?= number_format($makanan['harga'], 0, ',', '.'); ?></div>
         </div>
      </form>
      <?php
          }
      } else {
          echo '<p class="empty">No dishes available yet!</p>';
      }
      ?>

   </div>

</section>
<footer class="footer bg-dark text-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <h4 class="mb-3">Opsi Pembayaran</h4>
                <div class="d-flex">
                    <a href="#" target="_blank" class="me-2">
                        <img src="images/COD.png" alt="COD Icon">
                    </a>
                    <a href="#" target="_blank">
                        <img src="images/Qris.png" alt="QRIS Icon">
                    </a>
                </div>
            </div>
            <div class="col-sm-3">
                <h4 class="mb-3">Kontak</h4>
                <p>Citeureup</p>
                <p>No Telepon: <a href="tel:08121212121" class="text-light">0812-1212-12121</a></p>
            </div>
            <div class="col-sm-3">
                <h4 class="mb-3">Tentang Kami</h4>
                <p>Kami menyediakan layanan antar makanan yang cepat, aman, dan efisien dengan menjunjung kualitas serta kepuasan pelanggan.</p>
            </div>
            <div class="col-sm-3">
                <h4 class="mb-3">Lokasi Kami</h4>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31688.293142064143!2d107.52052183967008!3d-6.8862144526210045!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e43e8ebf7617%3A0x501e8f1fc2974e0!2sCimahi%2C%20Kec.%20Cimahi%20Tengah%2C%20Kota%20Cimahi%2C%20Jawa%20Barat!5e0!3m2!1sid!2sid!4v1734398342096!5m2!1sid!2sid" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>

        <div class="text-center mt-4 border-top pt-3">
            <p class="mb-0">&copy; 2024 Antar Makanan Online. All Rights Reserved.</p>
        </div>
    </div>
</footer>



<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="js/index.js"></script>

<script>

var swiper = new Swiper(".hero-slider", {
   loop:true,
   grabCursor: true,
   effect: "flip",
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
});

</script>

</script>
</body>
</html>
