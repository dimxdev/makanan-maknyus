<?php
include("../Koneksi/koneksi.php");
error_reporting(0);
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>All Orders</title>
    <link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/tabelorder.css" rel="stylesheet">
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
                                    <h4 class="m-b-0 text-white">All Orders</h4>
                                </div>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>No Order</th>    
                                                <th>User</th>       
                                                <th>Nama Makanan</th>
                                                <th>Jumlah</th>
                                                <th>Total Harga</th>
                                                <th>Alamat</th>
                                                <th>Status</th>                                                
                                                <th>Jasa Antar</th>
                                                <th>Waktu Pemesanan</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sql = "
                                            SELECT 
                                                users.nama_user, 
                                                makanan.nama_makanan, 
                                                order_item.jumlah, 
                                                (order_item.jumlah * makanan.harga) AS total_harga, 
                                                users.alamat, 
                                                delivery.status AS delivery_status, 
                                                orders.date,
                                                orders.id_order,
                                                jasaantar.nama_jasa
                                            FROM order_item
                                            INNER JOIN orders ON order_item.id_order = orders.id_order
                                            INNER JOIN makanan ON order_item.id_makanan = makanan.id_makanan
                                            INNER JOIN users ON orders.id_user = users.id_user
                                            LEFT JOIN delivery ON orders.id_order = delivery.id_order
                                            LEFT JOIN jasaantar ON delivery.id_jasaantar = jasaantar.id_jasaantar
                                            ORDER BY orders.date DESC
                                        ";
                                        $query = mysqli_query($db, $sql);

                                        if (!mysqli_num_rows($query) > 0) {
                                            echo '<td colspan="10"><center>No Orders</center></td>';
                                        } else {                
                                            while ($rows = mysqli_fetch_array($query)) {
                                                echo ' <tr>
                                                        <td>'.$rows['id_order'].'</td>
                                                        <td>'.$rows['nama_user'].'</td>
                                                        <td>'.$rows['nama_makanan'].'</td>
                                                        <td>'.$rows['jumlah'].'</td>
                                                        <td>Rp. '.number_format($rows['total_harga'], 2, ',', '.').'</td>
                                                        <td>'.$rows['alamat'].'</td>';

                                                // Menentukan status pengiriman
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

                                                // Menampilkan nama jasa antar
                                                $jasaantar = $rows['nama_jasa'] ? $rows['nama_jasa'] : 'N/A';  // Jika nama_jasa kosong, tampilkan 'N/A'
                                                echo '<td>'.$jasaantar.'</td>';  // Kolom Jasa Antar

                                                echo '<td>'.$rows['date'].'</td>';
                                                echo '<td>
                                                        <a href="delete_orders.php?order_del='.$rows['id_order'].'" onclick="return confirm(\'Are you sure?\');" class="btn btn-danger btn-flat btn-addon btn-xs m-b-10"><i class="fa fa-trash-o" style="font-size:16px"></i></a> 
                                                        <a href="view_order.php?user_upd='.$rows['id_order'].'" class="btn btn-info btn-flat btn-addon btn-sm m-b-10 m-l-5"><i class="fa fa-edit"></i></a>
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
