<?php
include __DIR__ . '/../components/connect.php';
  // if(isset($_COOKIE['tutor_id'])){
  //   $tutor_id = $_COOKIE['tutor_id'];
  // }else{
  //   $tutor_id='';
  //   header('location:login.php');
  //   exit();
  // }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
    <?php include __DIR__ . '/../components/admin_header.php'; ?>
    <script src="/../js/admin_script.js"></script>
</body>
</html>
