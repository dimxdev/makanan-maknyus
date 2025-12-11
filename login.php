<?php
session_start();
include("Koneksi/koneksi.php");
error_reporting(0);

// Jika pengguna sudah login, arahkan ke halaman utama
if (isset($_SESSION["id_user"])) {
    header("Location: index.php");
    exit();
}

// Variabel untuk pesan error
$message = "";

// Proses login
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        // Query untuk memeriksa kredensial pengguna
        $loginquery = "SELECT * FROM users WHERE nama_user = ? AND pass_user = ?";
        $stmt = $db->prepare($loginquery);
        $passwordHash = md5($password);
        $stmt->bind_param("ss", $username, $passwordHash);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            // Login berhasil
            $_SESSION["id_user"] = $row['id_user'];

            // Redirect ke halaman sebelumnya atau index.php
            $redirect = isset($_SESSION['redirect_to']) ? $_SESSION['redirect_to'] : 'index.php';
            unset($_SESSION['redirect_to']);
            header("Location: $redirect");
            exit();
        } else {
            // Pesan kesalahan jika login gagal
            $message = "Invalid Username or Password!";
        }
    } else {
        $message = "Please fill in all fields!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/login.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
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

    <div style="background-image: url('images/img/pimg.jpg');">
        <div class="module form-module">
            <div class="toggle"></div>
            <div class="form">
                <h2>Login to your account</h2>
                <?php if ($message): ?>
                    <span style="color:red;" id="error-message"><?php echo $message; ?></span>
                <?php endif; ?>
                <form action="" method="post">
                    <input type="text" placeholder="Username" name="username" required />
                    <input type="password" placeholder="Password" name="password" required />
                    <input type="submit" id="button" name="submit" value="Login" />
                </form>
            </div>
            <div class="cta">Not registered? <a href="registration.php" style="color:#5c4ac7;">Create an account</a></div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</body>
</html>
