<?php
  include '../components/connect.php';
  if(isset($_COOKIE['tutor_id'])){
    $tutor_id = $_COOKIE['tutor_id'];
  }else{
    $tutor_id='';
    header('location:login.php');
  }
?>
<style type="text/css">
  <?php 
  include '../css/admin_style.css';
  ?>
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dashboard</title>
<!-- boxicon link -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<!-- custom css link -->
 <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
    <?php include '../components/admin.header.php' ?>
    <script type="text/javascript" src="../js/admin_script.js"></script>
</body>
</html>