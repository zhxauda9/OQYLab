<?php
include __DIR__ . '/../components/connect.php';
if(isset($_POST['submit'])){

    $email=$_POST['email'];
    $email  =filter_var($email,FILTER_SANITIZE_STRING);

    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);    

    $select_tutor=$conn->prepare("SELECT * FROM `tutors` WHERE email=? AND password=? LIMIT 1");
    $select_tutor->execute([$email, $pass]);
    $row= $select_tutor->fetch(PDO::FETCH_ASSOC);

    if($select_tutor->rowCount()> 0){
        setcookie('tutor_id',$row['id'],time()+60*60*24*30,'/');
        header('location:dashboard.php');
    }else{
        $message[]='incorrect email or password';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin login</title>
    <!-- boxicon link -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- custom css link -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
    <?php
        if (isset($message)){
            foreach ($message as $message){
                echo '
                <div class="message">
                    <span>'.$message.'</span>
                    <i class="bx bx-x" onclick="this.parentElement.remove();"></i>
                </div>
                ';
            }
        }
        
    ?>
    <div class="form-container">
        <img src="../image/fun.jpg" class="form-img" style="left:4%;">
        <form action="" method="post" enctype="multipart/form-data" class="login">
            <h3>login now</h3>
            <p>your name<span>*</span></p>
            <input type="text" name="name" placeholder="enter your name" maxlength="50" required class="box">
            <p>your email<span>*</span></p>
            <input type="email" name="email" placeholder="enter your email" maxlength="20" required class="box">
            <p>your password<span>*</span></p>
            <input type="password" name="pass" placeholder="enter your password" maxlength="20" required class="box">
            <p class="link">do not have an account ? <<a href="register.php">register now</a></p>
            <input type="submit" name="submit" class="btn" value="login now">
        </form>
    </div>
</body>
</html>