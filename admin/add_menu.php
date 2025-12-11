<?php
include("../Koneksi/koneksi.php");
error_reporting(0);
session_start();

if (empty($_SESSION["adm_id"])) {
    header('location:index.php');
    exit();
}

$success = '';
$error = '';

if (isset($_POST['submit'])) {
    // Mengambil dan menyaring input pengguna
    $nama_makanan = trim($_POST['nama_makanan']);
    $deskripsi = trim($_POST['deskripsi']);
    $harga = trim($_POST['harga']);

    // Validasi input
    if (empty($nama_makanan) || empty($deskripsi) || empty($harga)) {
        $error = "<div class='alert alert-danger'>Semua kolom harus diisi.</div>";
    } else {
        // Mengelola upload gambar
        if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
            $img = $_FILES['img']['name'];
            $temp = $_FILES['img']['tmp_name'];
            $fsize = $_FILES['img']['size'];
            $extension = strtolower(pathinfo($img, PATHINFO_EXTENSION));

            // Validasi tipe file
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($extension, $allowedTypes)) {
                $error = "<div class='alert alert-danger'>Hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.</div>";
            } elseif ($fsize > 2 * 1024 * 1024) { // Maksimum 2MB
                $error = "<div class='alert alert-danger'>Ukuran gambar maksimum adalah 2MB.</div>";
            } else {
                // Membuat nama file unik untuk mencegah duplikasi
                $newFilename = uniqid() . "." . $extension;
                $target = "../images/makanan/" . $newFilename;

                // Memindahkan file ke direktori tujuan
                if (move_uploaded_file($temp, $target)) {
                    // Menggunakan prepared statement untuk keamanan
                    $stmt = $db->prepare("INSERT INTO makanan (nama_makanan, deskripsi, harga, img) VALUES (?, ?, ?, ?)");
                    if ($stmt) {
                        $stmt->bind_param("ssds", $nama_makanan, $deskripsi, $harga, $newFilename);
                        if ($stmt->execute()) {
                            $success = "<div class='alert alert-success'>Menu berhasil ditambahkan!</div>";
                        } else {
                            $error = "<div class='alert alert-danger'>Terjadi kesalahan saat menambahkan menu: " . htmlspecialchars($stmt->error) . "</div>";
                            // Hapus file jika insert gagal
                            unlink($target);
                        }
                        $stmt->close();
                    } else {
                        $error = "<div class='alert alert-danger'>Terjadi kesalahan pada database.</div>";
                        // Hapus file jika prepare statement gagal
                        unlink($target);
                    }
                } else {
                    $error = "<div class='alert alert-danger'>Gagal mengunggah gambar.</div>";
                }
            }
        } else {
            $error = "<div class='alert alert-danger'>Gambar harus diunggah.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Menu</title>
    <link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
			<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>
    <div id="main-wrapper">
        <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <div class="navbar-header">
                    <a class="navbar-brand" href="dashboard.php">                      
                        <span><img src="../images/icn.png" alt="homepage" class="dark-logo" /></span>
                    </a>
                </div>
                <div class="navbar-collapse">
                    <ul class="navbar-nav mr-auto mt-md-0"></ul>
                    <ul class="navbar-nav my-lg-0">
                        <li class="nav-item dropdown">
                            <div class="dropdown-menu dropdown-menu-right mailbox animated zoomIn">
                                <ul>
                                    <li><div class="drop-title">Notifications</div></li>
                                    <li><a class="nav-link text-center" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="../images/bookingSystem/user-icn.png" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                                <ul class="dropdown-user">
                                    <li><a href="logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>

        <div class="left-sidebar">
            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-devider"></li>
                        <li><a href="dashboard.php"><i class="fa fa-tachometer"></i><span>Dashboard</span></a></li>
                        <li><a href="all_users.php"><span><i class="fa fa-user f-s-20"></i></span><span>Users</span></a></li>
                        <li class="has-arrow" aria-expanded="false">
                            <a href="#" aria-expanded="false"><i class="fa fa-cutlery" aria-hidden="true"></i><span class="hide-menu">Menu</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="all_menu.php">Menu Makanan</a></li>
                                <li><a href="add_menu.php">Penambahan Makanan</a></li>
                            </ul>
                        </li>
                        <li><a href="all_orders.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span>Orders</span></a></li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="col-lg-12">
                            <div class="card card-outline-primary">
                                <div class="card-header">
                                    <h4 class="m-b-0 text-white">Add Menu</h4>
                                </div>
                                <hr>
                                <div class="card-body">
                                    <form method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="nama_makanan">Nama Makanan</label>
                                            <input type="text" class="form-control" name="nama_makanan" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="deskripsi">Deskripsi</label>
                                            <textarea class="form-control" name="deskripsi" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="harga">Harga</label>
                                            <input type="number" class="form-control" name="harga" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="img">Gambar</label>
                                            <input type="file" class="form-control" name="img" required>
                                        </div>
                                        <button type="submit" name="submit" class="btn btn-primary">Add Menu</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/hasarrow.js"></script>
</body>
</html>