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
    <title>View Order</title>
    <link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script language="javascript" type="text/javascript">
        var popUpWin=0;
        function popUpWindow(URLStr, left, top, width, height)
        {
            if(popUpWin)
            {
                if(!popUpWin.closed) popUpWin.close();
            }
            popUpWin = open(URLStr,'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=yes,width='+1000+',height='+1000+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
        }
    </script>
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
                                    <h4 class="m-b-0 text-white">View Order</h4>
                                </div>
                                <div class="table-responsive m-t-20">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <tbody>
                                            <?php
                                            // Ambil id_order dari parameter URL
                                            $order_id = $_GET['user_upd'];

                                            // Query untuk mengambil data order dan informasi terkait
                                            $sql = "
                                                SELECT 
                                                    users.*, 
                                                    orders.*, 
                                                    order_item.*, 
                                                    makanan.nama_makanan, 
                                                    makanan.harga, 
                                                    delivery.status AS delivery_status  -- Menambahkan kolom status delivery
                                                FROM orders 
                                                INNER JOIN order_item ON orders.id_order = order_item.id_order 
                                                INNER JOIN users ON orders.id_user = users.id_user 
                                                INNER JOIN makanan ON order_item.id_makanan = makanan.id_makanan 
                                                LEFT JOIN delivery ON orders.id_order = delivery.id_order  -- LEFT JOIN dengan tabel Delivery
                                                WHERE orders.id_order = '$order_id'";

                                            $query = mysqli_query($db, $sql);

                                            // Periksa apakah query berhasil dan ambil hasilnya
                                            if ($query) {
                                                $rows = mysqli_fetch_array($query);
                                            } else {
                                                // Jika query gagal, tampilkan pesan error
                                                echo "Error: " . mysqli_error($db);
                                            }
                                            ?>
                                            <tr>
                                                <td><strong>Status:</strong></td>
                                                <td><center>
                                                    <?php
                                                    $status = $rows['delivery_status'];
                                                    if ($status == "on the way") {
                                                        echo '<button type="button" class="btn btn-warning"><span class="fa fa-cog fa-spin" aria-hidden="true"></span> On The Way!</button>';
                                                    } elseif ($status == "delivered") {
                                                        echo '<button type="button" class="btn btn-primary"><span class="fa fa-check-circle" aria-hidden="true"></span> Delivered</button>';
                                                    } elseif ($status == "cancelled") {
                                                        echo '<button type="button" class="btn btn-danger"><i class="fa fa-close"></i> Cancelled</button>';
                                                    }
                                                    ?>
                                                </center></td>
                                                <td><center>
                                                    <form action="update_order.php" method="POST">
                                                        <input type="hidden" name="order_id" value="<?php echo $rows['id_order']; ?>" />
                                                        <select name="delivery_status" class="form-control" style="width: 200px;">
                                                            <option value="on the way" <?php echo ($status == 'on the way') ? 'selected' : ''; ?>>On The Way</option>
                                                            <option value="delivered" <?php echo ($status == 'delivered') ? 'selected' : ''; ?>>Delivered</option>
                                                            <option value="cancelled" <?php echo ($status == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                                        </select>
                                                        <button type="submit" class="btn btn-primary mt-2">Update Status</button>
                                                    </form>
                                                </center></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Username:</strong></td>
                                                <td><center><?php echo $rows['nama_user']; ?></center></td>                                                                                  
                                            </tr>  
                                            <tr>
                                                <td><strong>Nama Makanan:</strong></td>
                                                <td><center><?php echo $rows['nama_makanan']; ?></center></td>                                                                
                                            </tr>    
                                            <tr>
                                                <td><strong>Jumlah:</strong></td>
                                                <td><center><?php echo $rows['jumlah']; ?></center></td>                                                                                            
                                            </tr>
                                            <tr>
                                                <td><strong>Harga:</strong></td>
                                                <td><center>Rp.<?php echo $rows['harga']; ?></center></td>                                                                                            
                                            </tr>
                                            <tr>
                                                <td><strong>Alamat:</strong></td>
                                                <td><center><?php echo $rows['alamat']; ?></center></td>                                                                                        
                                            </tr>
                                            <tr>
                                                <td><strong>Waktu:</strong></td>
                                                <td><center><?php echo $rows['date']; ?></center></td>                                                                                        
                                            </tr>
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
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/hasarrow.js"></script>
</body>
</html>
