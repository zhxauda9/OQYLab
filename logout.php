<?php
include __DIR__ . '/components/connect.php';
setcookie('user_id','', time() -1,'/');
header('location:login.php');
?>