<?php
include __DIR__ . '/components/connect.php';
if(isset($_POST['submit'])){

    $email=$_POST['email'];
    $email  =filter_var($email,FILTER_SANITIZE_SPECIAL_CHARS);

    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_SPECIAL_CHARS);    

    $select_user=$conn->prepare("SELECT * FROM `users` WHERE email=? AND password=? LIMIT 1");
    $select_user->execute([$email, $pass]);
    $row= $select_user->fetch(PDO::FETCH_ASSOC);

    if($select_user->rowCount()> 0){
        setcookie('user_id',$row['id'],time()+60*60*24*30,'/');
        header('location:profile.php');
    }else{
        $message[]='incorrect email or password';
    }
}
?>
<style>
  <?php include 'css/user_style.css'; ?>
  </style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OQYLab</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<?php include __DIR__ . '/components/user_header.php'; ?>
<div class="form-container">
<div class="heading">
        <span>oqylab</span>
        <h1>Кіру</h1>
        </div>
        <form action="" method="post" enctype="multipart/form-data" class="login">
            <h3>Кіру</h3>
            <p>Атыңыз<span>*</span></p>
            <input type="text" name="name" placeholder="Атыңызды енгізіңіз" maxlength="50" required class="box">
            <p>Электронды пошта<span>*</span></p>
            <input type="email" name="email" placeholder="Электронды поштаңызды енгізіңіз" maxlength="50" required class="box">
            <p>Құпиясөз<span>*</span></p>
            <input type="password" name="pass" placeholder="Құпиясөзіңізді енгізіңіз" maxlength="20" required class="box">
            <p class="link">Аккаунтыңыз жоқ па? <<a href="register.php">Тіркеліңіз</a></p>
            <input type="submit" name="submit" class="btn" value="Кіру">
        </form>
    </div>

<?php include __DIR__ . '/components/footer.php'; ?>
<script src="/js/user_script.js"></script>
</body>
</html>