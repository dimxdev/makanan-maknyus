<?php
// Mulai sesi dan atur pelaporan kesalahan
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sertakan file koneksi database
include("../Koneksi/koneksi.php"); // Pastikan path ini benar

// Cek apakah admin sudah login
if (empty($_SESSION["adm_id"])) {
    header("Location: index.php");
    exit();
}

// Inisialisasi variabel pesan
$error = '';
$success = '';

// Cek apakah parameter 'user_upd' ada dan valid
if (!isset($_GET['user_upd']) || !filter_var($_GET['user_upd'], FILTER_VALIDATE_INT)) {
    header("Location: all_users.php?msg=Invalid User ID");
    exit();
}

$user_id = $_GET['user_upd'];

// Ambil data pengguna yang akan diupdate
$stmt = $db->prepare("SELECT * FROM users WHERE id_user = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: all_users.php?msg=User Not Found");
    exit();
}

$user = $result->fetch_assoc();
$stmt->close();

// Proses form ketika disubmit
if(isset($_POST['submit'])) {
    // Ambil dan sanitasi input
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);

    // Validasi input
    if(empty($fname) || empty($lname) || empty($email) || empty($phone)) {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>All fields except password are required!</strong>
                  </div>';
    } else {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = '<div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Invalid email format!</strong>
                      </div>';
        } elseif(strlen($phone) < 10) {
            $error = '<div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Phone number must be at least 10 digits!</strong>
                      </div>';
        } else {
            // Mulai transaksi untuk menjaga konsistensi data
            mysqli_begin_transaction($db);

            try {
                // Cek apakah email sudah digunakan oleh pengguna lain
                $stmt_email = $db->prepare("SELECT id_user FROM users WHERE email = ? AND id_user != ?");
                $stmt_email->bind_param("si", $email, $user_id);
                $stmt_email->execute();
                $stmt_email->store_result();

                if($stmt_email->num_rows > 0) {
                    throw new Exception("Email is already taken by another user.");
                }
                $stmt_email->close();

                // Persiapkan query update
                if(!empty($password)) {
                    // Jika password diubah, hash password baru
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt_update = $db->prepare("UPDATE users SET f_name = ?, l_name = ?, email = ?, no_tlp = ?, pass_user = ? WHERE id_user = ?");
                    $stmt_update->bind_param("sssssi", $fname, $lname, $email, $phone, $hashed_password, $user_id);
                } else {
                    // Jika password tidak diubah
                    $stmt_update = $db->prepare("UPDATE users SET f_name = ?, l_name = ?, email = ?, no_tlp = ? WHERE id_user = ?");
                    $stmt_update->bind_param("ssssi", $fname, $lname, $email, $phone, $user_id);
                }

                if(!$stmt_update->execute()) {
                    throw new Exception("Failed to update user: " . $stmt_update->error);
                }

                $stmt_update->close();

                // Commit transaksi
                mysqli_commit($db);

                // Update data pengguna untuk menampilkan perubahan
                $user['f_name'] = $fname;
                $user['l_name'] = $lname;
                $user['email'] = $email;
                $user['no_tlp'] = $phone;

                $success = '<div class="alert alert-success alert-dismissible fade show">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <strong>User Updated Successfully!</strong>
                           </div>';
            } catch (Exception $e) {
                // Rollback transaksi jika terjadi kesalahan
                mysqli_rollback($db);
                $error = '<div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '
                          </div>';
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
    <title>Update User</title>
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
                <!-- Tampilkan pesan error atau sukses -->
                <?php
                    if(!empty($error)) {
                        echo $error;
                    }
                    if(!empty($success)) {
                        echo $success;
                    }
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-outline-primary">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white">Update User</h4>
                            </div>
                            <div class="card-body">
                                <form action="" method="post">
                                    <div class="form-body">
                                        <hr>
                                        <div class="row p-t-20">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">First Name</label>
                                                    <input type="text" name="fname" class="form-control" value="<?php echo htmlspecialchars($user['f_name']); ?>" placeholder="First Name" maxlength="20" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Last Name</label>
                                                    <input type="text" name="lname" class="form-control" value="<?php echo htmlspecialchars($user['l_name']); ?>" placeholder="Last Name" maxlength="20" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row p-t-20">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Email</label>
                                                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="example@gmail.com" maxlength="50" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Phone</label>
                                                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['no_tlp']); ?>" placeholder="Phone" maxlength="20" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row p-t-20">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Password</label>
                                                    <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password" minlength="6">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <input type="submit" name="submit" class="btn btn-primary" value="Save"> 
                                        <a href="all_users.php" class="btn btn-inverse">Cancel</a>
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
