<?php
include("../Koneksi/koneksi.php");
error_reporting(0);
session_start();

if(empty($_SESSION["adm_id"])) {
    header('location:index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel</title>
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
                            <a class="nav-link dropdown-toggle text-muted" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="../images/bookingSystem/user-icn.png" alt="user" class="profile-pic" /></a>
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
                <div class="col-lg-12">
                    <div class="card card-outline-primary">
                        <div class="card-header">
                            <h4 class="m-b-0 text-white">Dashboard</h4>
                        </div>
                        <div class="row">
					        <div class="col-md-3">
                                <div class="card p-30">
                                    <div class="media">
                                        <div class="media-left meida media-middle">
                                            <span><i class="fa fa-cutlery f-s-40" aria-hidden="true"></i></span>
                                        </div>
                                        <div class="media-body media-text-right">
                                            <h2><?php
                                                // Menampilkan jumlah makanan
                                                $sql="SELECT * FROM makanan";
                                                $result=mysqli_query($db, $sql); 
                                                $rws=mysqli_num_rows($result);
                                                echo $rws;
                                            ?></h2>
                                            <p class="m-b-0">Makanan</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card p-30">
                                    <div class="media">
                                        <div class="media-left meida media-middle">
                                            <span><i class="fa fa-users f-s-40"></i></span>
                                        </div>
                                        <div class="media-body media-text-right">
                                            <h2><?php
                                                // Menampilkan jumlah users
                                                $sql="SELECT * FROM users";
                                                $result=mysqli_query($db, $sql); 
                                                $rws=mysqli_num_rows($result);
                                                echo $rws;
                                            ?></h2>
                                            <p class="m-b-0">Users</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
					        <div class="col-md-3">
                                <div class="card p-30">
                                    <div class="media">
                                        <div class="media-left meida media-middle"> 
                                            <span><i class="fa fa-shopping-cart f-s-40" aria-hidden="true"></i></span>
                                        </div>
                                        <div class="media-body media-text-right">
                                            <h2><?php
                                                // Menampilkan total orders
                                                $sql="SELECT * FROM orders";
                                                $result=mysqli_query($db, $sql); 
                                                $rws=mysqli_num_rows($result);
                                                echo $rws;
                                            ?></h2>
                                            <p class="m-b-0">Total Orders</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card p-30">
                                    <div class="media">
                                        <div class="media-left meida media-middle"> 
                                            <span><i class="fa fa-money f-s-40" aria-hidden="true"></i></span>
                                        </div>
                                    <div class="media-body media-text-right">
                                        <h2>Rp. <?php
                                            $result = mysqli_query($db, 'SELECT SUM(oi.jumlah * m.harga) AS value_sum
                                                                        FROM Orders o
                                                                        JOIN Delivery d ON o.id_order = d.id_order
                                                                        JOIN order_item oi ON o.id_order = oi.id_order
                                                                        JOIN makanan m ON oi.id_makanan = m.id_makanan
                                                                        WHERE d.status = "delivered"'); 

                                            $row = mysqli_fetch_assoc($result); 
                                            $sum = $row['value_sum'] ? $row['value_sum'] : 0;

                                            // Format output
                                            if ($sum == 0) {
                                                echo '0'; 
                                            } else {
                                                if (floor($sum) == $sum) {
                                                    echo '' . number_format(floor($sum), 0, ',', '.');
                                                } else {
                                                    echo '' . number_format($sum, 2, ',', '.');
                                                }
                                            }
                                            ?>
                                        </h2>
                                        <p class="m-b-0">Pendapatan</p>
                                    </div>
                                </div>
                            </div>
                        </div>	                   
                    </div>     

                <div class="row">
                    <div class="col-md-4">
                        <div class="card p-30">
                            <div class="media">
                                <div class="media-left meida media-middle"> 
                                    <span><i class="fa fa-spinner f-s-40" aria-hidden="true"></i></span>
                                </div>
                                <div class="media-body media-text-right">
                                    <h2><?php
                                        // Menampilkan jumlah pesanan yang sedang diproses
                                        $sql="SELECT * FROM Delivery WHERE status = 'on the way'";
                                        $result=mysqli_query($db, $sql); 
                                        $rws=mysqli_num_rows($result);
                                        echo $rws;
                                    ?></h2>
                                    <p class="m-b-0">Processing Orders</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card p-30">
                            <div class="media">
                                <div class="media-left meida media-middle"> 
                                    <span><i class="fa fa-check f-s-40" aria-hidden="true"></i></span>
                                </div>
                                <div class="media-body media-text-right">
                                    <h2><?php
                                        // Menampilkan jumlah pesanan yang sudah selesai
                                        $sql="SELECT * FROM Delivery WHERE status = 'delivered'";
                                        $result=mysqli_query($db, $sql); 
                                        $rws=mysqli_num_rows($result);
                                        echo $rws;
                                    ?></h2>
                                    <p class="m-b-0">Delivered Orders</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-30">
                            <div class="media">
                                <div class="media-left meida media-middle"> 
                                    <span><i class="fa fa-times f-s-40" aria-hidden="true"></i></span>
                                </div>
                                <div class="media-body media-text-right">
                                    <h2><?php
                                        // Menampilkan jumlah pesanan yang dibatalkan
                                        $sql="SELECT * FROM Delivery WHERE status = 'cancelled'";
                                        $result=mysqli_query($db, $sql); 
                                        $rws=mysqli_num_rows($result);
                                        echo $rws;
                                    ?></h2>
                                    <p class="m-b-0">Cancelled Orders</p>
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
