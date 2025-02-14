<?php
    include __DIR__ . '/components/connect.php';

    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    } else {
        $user_id = '';
    }

    function generate_unique_id($conn) {
        do {
            $id = substr(sha1(uniqid(mt_rand(), true)), 0, 20);
            $check_id = $conn->prepare("SELECT id FROM `users` WHERE id = ?");
            $check_id->execute([$id]);
        } while ($check_id->rowCount() > 0); 
    
        return $id;
    }
    function generateShortFilename($filename) {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        return uniqid() . bin2hex(random_bytes(4)) . '.' . $ext;
    }
    
    if(isset($_POST['submit'])){
        $id = generate_unique_id($conn);
    
        $name = trim($_POST['name']);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    
        $pass = sha1($_POST['pass']);
        $cpass = sha1($_POST['cpass']);
    
        if (!$email) {
            $message = 'Invalid email format';
        } else {
            $select_tutor = $conn->prepare("SELECT * FROM `users` WHERE email=?");
            $select_tutor->execute([$email]);
    
            if ($select_tutor->rowCount() > 0) {
                $message = 'Email already exists';
            } else {
                if ($pass !== $cpass) {
                    $message = 'Confirm password does not match';
                } else {
                    if (!empty($_FILES['image']['name'])) {
                        $image = $_FILES['image']['name'];
                        $image = filter_var($image, FILTER_SANITIZE_SPECIAL_CHARS);
                        $rename = generateShortFilename($image);
                        $image_tmp_name = $_FILES['image']['tmp_name'];
                        $image_folder = __DIR__ . 'uploaded_files/' . $rename;
                        $uploadDir = __DIR__ . 'uploaded_files/';
    
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
    
                        if (move_uploaded_file($image_tmp_name, $image_folder)) {
                            $insert_user = $conn->prepare('INSERT INTO `users`(id, name, email, password, image) VALUES(?,?,?,?,?)');
                            $insert_user->execute([$id, $name, $email, $pass, $rename]);
                            $message = 'New user registered! You can login now';
                        } else {
                            $message = 'Failed to upload image';
                        }
                    } else {
                        $message = 'Please select an image';
                    }
                }
            }
        }
        header('location:login.php');
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
    <title>Document</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<?php include __DIR__ . '/components/user_header.php'; ?>

<section class="form-container">
    <div class="heading">
        <span>join oqylab</span>
        <h1>create account</h1>
    </div>
    <form action="" action="" method="post" enctype="multipart/form-data">
        <div class="flex">
            <div class="col">
            <p>your name<span>*</span></p>
            <input type="text" name="name" placeholder="enter your name" maxlength="50" required class="box">
            <p>your email<span>*</span></p>
            <input type="email" name="email" placeholder="enter your email" maxlength="50" required class="box">    
            </div>
            <div class="col">
                `<p>your password<span>*</span></p>
                <input type="password" name="pass" placeholder="enter your password" maxlength="50" required class="box">
                <p>your password<span>*</span></p>
                <input type="password" name="cpass" placeholder="confirm your password" maxlength="50" required class="box">
            </div>
        </div>
        <p>select pic<span>*</span></p>
        <input type="file" name="image" accept="image/*" required class="box">
        <p class="link">already have an account ? 
            <a href="login.php">login now</a></p>
            <input type="submit" name="submit" class="btn" value="register now">
    </form>
    </section>
    <?php include __DIR__ . '/components/footer.php'; ?>
<script src="/js/user_script.js"></script>
</body>
</html>