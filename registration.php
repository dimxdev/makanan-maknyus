<?php
include("Koneksi/koneksi.php");
error_reporting(0);
session_start();

if (isset($_POST['submit'])) {
  if (empty($_POST['username']) || 
      empty($_POST['firstname']) || 
      empty($_POST['lastname']) || 
      empty($_POST['email']) ||  
      empty($_POST['phone']) || 
      empty($_POST['password']) || 
      empty($_POST['cpassword']) || 
      empty($_POST['address'])) {
      $message = "All fields must be Required!";
  } else {
    $check_email = mysqli_query($db, "SELECT email FROM users WHERE email = '".$_POST['email']."'");
    $check_phone = mysqli_query($db, "SELECT no_tlp FROM users WHERE no_tlp = '".$_POST['phone']."'");
    $check_username = mysqli_query($db, "SELECT nama_user FROM users WHERE nama_user = '".$_POST['username']."'");

    if ($_POST['password'] != $_POST['cpassword']) {
      echo "<script>alert('Password does not match');</script>";
    } elseif (strlen($_POST['password']) < 6) {
      echo "<script>alert('Password must be at least 6 characters');</script>";
    } elseif (strlen($_POST['phone']) < 10) {
      echo "<script>alert('Invalid phone number');</script>";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      echo "<script>alert('Invalid email address');</script>";
    } elseif (mysqli_num_rows($check_email) > 0) {
      echo "<script>alert('Email already exists');</script>";
    } elseif (mysqli_num_rows($check_phone) > 0) {
      echo "<script>alert('Phone number already exists');</script>";
    } elseif (mysqli_num_rows($check_username) > 0) {
      echo "<script>alert('Username already exists');</script>";
    } else {
      // Insert data into the new `users` table structure, including username
      $password_hash = md5($_POST['password']);  // It's better to use password_hash() for security in production
      $mql = "INSERT INTO users(nama_user, f_name, l_name, email, no_tlp, pass_user, alamat) 
              VALUES('".$_POST['username']."', '".$_POST['firstname']."', '".$_POST['lastname']."', '".$_POST['email']."', '".$_POST['phone']."', '".$password_hash."', '".$_POST['address']."')";
      if (mysqli_query($db, $mql)) {
        header("Location: login.php"); // Redirect after successful registration
      } else {
        echo "<script>alert('Error occurred while registering user');</script>";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/registration.css">
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
    <div class="pen-title"></div>
    <div class="module form-module">
      <div class="form">
        <h2>Register Account</h2>
        <form method="POST" action="registration.php">
          <div class="row">
            <div class="col-sm-12">
              <input type="text" placeholder="Username" name="username" required />
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <input type="text" placeholder="First Name" name="firstname" required />
            </div>
            <div class="col-sm-6">
              <input type="text" placeholder="Last Name" name="lastname" required />
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <input type="email" placeholder="Email" name="email" required />
            </div>
            <div class="col-sm-6">
              <input type="tel" placeholder="Phone Number" name="phone" required />
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <input type="password" placeholder="Password" name="password" required />
            </div>
            <div class="col-sm-6">
              <input type="password" placeholder="Confirm Password" name="cpassword" required />
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <textarea name="address" placeholder="Address" rows="4" required></textarea>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <input type="submit" name="submit" value="Register" class="btn theme-btn" />
            </div>
          </div>
        </form>
      </div>
      <div class="cta">Already have an account? <a href="login.php" style="color:#5c4ac7;">Login</a></div>
    </div>
  </div>

</body>
</html>
