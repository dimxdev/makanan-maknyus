<?php
session_start();

// Pastikan file koneksi sudah sesuai dengan database yang ada
include("Koneksi/koneksi.php");

// Periksa apakah pengguna sudah login
if (empty($_SESSION['id_user'])) {
    header('location:login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>My Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link href="css/footer.css" rel="stylesheet">
    <style type="text/css">
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }
        .table th {
            background-color: #343a40;
            color: #fff;
            text-align: center;
        }
        .table td {
            text-align: center;
        }
        .table th, .table td {
            padding: 1rem;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
        }
        .btn {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
            margin: 0 0.2rem;
        }
        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .inner-page-hero {
            margin-top: -70px; /* Menghilangkan jarak dengan header */
        }
    </style>
</head>
<body>
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
<div class="page-wrapper">
    <div class="inner-page-hero bg-image" data-image-src="images/img/pimg.jpg">
        <div class="container"></div>
    </div>
    <div class="result-show">
        <div class="container">
            <div class="row"></div>
        </div>
    </div>
    <section class="orders-section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12"></div>
                <div class="col-xs-12">
                    <div class="bg-gray">
                        <div class="row">
                            <table id="myTable" class="table table-bordered table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Nama Makanan</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql = "
                                    SELECT 
                                        makanan.nama_makanan AS item, 
                                        order_item.jumlah AS quantity, 
                                        (order_item.jumlah * makanan.harga) AS price, 
                                        delivery.status AS delivery_status, 
                                        orders.date, 
                                        orders.id_order
                                    FROM order_item
                                    INNER JOIN orders ON order_item.id_order = orders.id_order
                                    INNER JOIN makanan ON order_item.id_makanan = makanan.id_makanan
                                    LEFT JOIN delivery ON orders.id_order = delivery.id_order
                                    ORDER BY orders.date DESC
                                ";                            
                                $query = mysqli_query($db, $sql);

                                if (!mysqli_num_rows($query) > 0) {
                                    echo '<td colspan="6"><center>No Orders</center></td>';
                                } else {                
                                    while ($rows = mysqli_fetch_array($query)) {
                                        echo ' <tr>
                                                <td>'.$rows['item'].'</td>
                                                <td>'.$rows['quantity'].'</td>
                                                <td>Rp. '.number_format($rows['price'], 2, ',', '.').'</td>';

                                        $status = $rows['delivery_status'];
                                        if (empty($status)) {
                                            echo '<td><button type="button" class="btn btn-info"><span class="fa fa-bars" aria-hidden="true"></span> Dispatch</button></td>';
                                        } elseif ($status == "on the way") {
                                            echo '<td><button type="button" class="btn btn-warning"><span class="fa fa-cog fa-spin" aria-hidden="true"></span> On The Way!</button></td>';
                                        } elseif ($status == "delivered") {
                                            echo '<td><button type="button" class="btn btn-primary"><span class="fa fa-check-circle" aria-hidden="true"></span> Delivered</button></td>';
                                        } elseif ($status == "cancelled") {
                                            echo '<td><button type="button" class="btn btn-danger"><i class="fa fa-close"></i> Cancelled</button></td>';
                                        }
                                        echo '<td>'.$rows['date'].'</td>';
                                        echo '<td>
                                                <a href="hapusorder.php?order_del='.$rows['id_order'].'" onclick="return confirm(\'Are you sure?\');" class="btn btn-danger btn-flat btn-addon btn-xs m-b-10"><i class="fa fa-trash-o" style="font-size:16px"></i></a> 
                                            </td>
                                            </tr>';
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<footer class="footer bg-dark text-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <h4 class="mb-2">Opsi Pembayaran</h4>
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
                <h4 class="mb-4">Kontak</h4>
                <p>Citeureup</p>
                <p>No Telepon: <a href="tel:08121212121" class="text-light">0812-1212-12121</a></p>
            </div>
            <div class="col-sm-3">
                <h4 class="mb-4">Tentang Kami</h4>
                <p>Kami menyediakan layanan antar makanan yang cepat, aman, dan efisien dengan menjunjung kualitas serta kepuasan pelanggan.</p>
            </div>
            <div class="col-sm-3">
                <h4 class="mb-4">Lokasi Kami</h4>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31688.293142064143!2d107.52052183967008!3d-6.8862144526210045!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e43e8ebf7617%3A0x501e8f1fc2974e0!2sCimahi%2C%20Kec.%20Cimahi%20Tengah%2C%20Kota%20Cimahi%2C%20Jawa%20Barat!5e0!3m2!1sid!2sid!4v1734398342096!5m2!1sid!2sid" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
        <div class="text-center mt-6 border-top pt-1 pb-0">
            <p class="mb-0">&copy; 2024 Antar Makanan Online. All Rights Reserved.</p>
        </div>
    </div>
</footer>  
<script src="js/jquery.min.js"></script>
<script src="js/tether.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/animsition.min.js"></script>
<script src="js/bootstrap-slider.min.js"></script>
<script src="js/jquery.isotope.min.js"></script>
<script src="js/headroom.js"></script>
<script src="js/foodpicky.min.js"></script>
</body>
</html>
