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

// Mendapatkan ID menu yang akan diupdate
$menu_id = isset($_GET['menu_upd']) ? intval($_GET['menu_upd']) : 0;

// Mengambil data menu saat ini
$stmt = $db->prepare("SELECT * FROM makanan WHERE id_makanan = ?");
$stmt->bind_param("i", $menu_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo "<script>alert('Menu tidak ditemukan.'); window.location.href='all_menu.php';</script>";
    exit();
}
$menu = $result->fetch_assoc();
$stmt->close();

if (isset($_POST['submit'])) {
    // Mengambil dan menyaring input pengguna
    $nama_makanan = trim($_POST['d_name']);
    $deskripsi = trim($_POST['about']);
    $harga = trim($_POST['price']);

    // Validasi input
    if (empty($nama_makanan) || empty($deskripsi) || empty($harga)) {
        $error = "<div class='alert alert-danger'>Semua kolom harus diisi.</div>";
    } else {
        $newFilename = $menu['img']; // Default gambar tetap

        // Memeriksa apakah ada gambar baru yang diunggah
        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $img = $_FILES['file']['name'];
            $temp = $_FILES['file']['tmp_name'];
            $fsize = $_FILES['file']['size'];
            $extension = strtolower(pathinfo($img, PATHINFO_EXTENSION));

            // Validasi tipe file
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($extension, $allowedTypes)) {
                $error = "<div class='alert alert-danger'>Hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.</div>";
            } elseif ($fsize > 2 * 1024 * 1024) { // Maksimum 2MB
                $error = "<div class='alert alert-danger'>Ukuran gambar maksimum adalah 2MB.</div>";
            } else {
                // Membuat nama file unik
                $newFilename = uniqid() . "." . $extension;
                $target = "../images/makanan/" . $newFilename;

                // Memindahkan file ke direktori tujuan
                if (move_uploaded_file($temp, $target)) {
                    // Menghapus gambar lama jika ada
                    if (!empty($menu['img']) && file_exists("../images/makanan/" . $menu['img'])) {
                        unlink("../images/makanan/" . $menu['img']);
                    }
                } else {
                    $error = "<div class='alert alert-danger'>Gagal mengunggah gambar.</div>";
                }
            }
        }

        if (empty($error)) {
            // Menggunakan prepared statement untuk keamanan
            $stmt = $db->prepare("UPDATE makanan SET nama_makanan = ?, deskripsi = ?, harga = ?, img = ? WHERE id_makanan = ?");
            if ($stmt) {
                $stmt->bind_param("ssdsi", $nama_makanan, $deskripsi, $harga, $newFilename, $menu_id);
                if ($stmt->execute()) {
                    $success = "<div class='alert alert-success'>Menu berhasil diperbarui.</div>";
                    // Mengambil data terbaru
                    $menu['nama_makanan'] = $nama_makanan;
                    $menu['deskripsi'] = $deskripsi;
                    $menu['harga'] = $harga;
                    $menu['img'] = $newFilename;
                } else {
                    $error = "<div class='alert alert-danger'>Terjadi kesalahan saat memperbarui menu: " . htmlspecialchars($stmt->error) . "</div>";
                }
                $stmt->close();
            } else {
                $error = "<div class='alert alert-danger'>Terjadi kesalahan pada database.</div>";
            }
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
    <title>Update Menu</title>
    <link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="fix-header">
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
                <?php  
                echo $error;
                echo $success; 
                ?>		
                <div class="col-lg-12">
                    <div class="card card-outline-primary">
                        <div class="card-header">
                            <h4 class="m-b-0 text-white">Update Menu</h4>
                        </div>
                        <div class="card-body">
                            <form action='' method='post'  enctype="multipart/form-data">
                                <div class="form-body">
                                    <?php 
                                    // Ambil data menu berdasarkan ID
                                    $qml ="select * from makanan where id_makanan='$_GET[menu_upd]'";
                                    $rest=mysqli_query($db, $qml); 
                                    $roww=mysqli_fetch_array($rest);
                                    ?>
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Nama Makanan</label>
                                                <input type="text" name="d_name" value="<?php echo $roww['nama_makanan'];?>" class="form-control" placeholder="Morzirella">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Harga </label>
                                                <input type="text" name="price" value="<?php echo $roww['harga'];?>"  class="form-control" placeholder="$">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row p-t-20">
                                        <div class="col-md-12">
                                            <div class="form-group has-danger">
                                                <label class="control-label">Deskripsi</label>
                                                <input type="text" name="about" value="<?php echo $roww['deskripsi'];?>" class="form-control form-control-danger" placeholder="slogan">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group has-danger">
                                                <label class="control-label">Image</label>
                                                <input type="file" name="file" id="lastName" class="form-control form-control-danger" placeholder="12n">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <input type="submit" name="submit" class="btn btn-primary" value="Save"> 
                                        <a href="add_menu.php" class="btn btn-inverse">Cancel</a>
                                    </div>
                                </form>
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