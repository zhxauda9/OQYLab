<?php
include __DIR__ . '/../components/connect.php';
if(isset($_POST['submit'])){
    $id = unique_id();

    $name = trim($_POST['name']);
    $profession = trim($_POST['profession']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    $pass = sha1($_POST['pass']);
    $cpass = sha1($_POST['cpass']);

    if (!$email) {
        $message = 'Invalid email format';
    } else {
        $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email=?");
        $select_tutor->execute([$email]);

        if ($select_tutor->rowCount() > 0) {
            $message = 'Email already exists';
        } else {
            if ($pass !== $cpass) {
                $message = 'Confirm password does not match';
            } else {
                // Проверяем, загружен ли файл
                if (!empty($_FILES['image']['name'])) {
                    $image = $_FILES['image']['name'];
                    $ext = pathinfo($image, PATHINFO_EXTENSION);  
                    $rename = unique_id() . '.' . $ext; // Генерируем уникальное имя
                    $image_size = $_FILES['image']['size'];
                    $image_tmp_name = $_FILES['image']['tmp_name'];
                    $image_folder = '../uploaded_files/' . $rename;

                    if (!is_dir('../uploaded_files')) {
                        mkdir('../uploaded_files', 0777, true); // Создаём папку, если её нет
                    }

                    if (move_uploaded_file($image_tmp_name, $image_folder)) {
                        $insert_tutor = $conn->prepare('INSERT INTO `tutors`(id, name, profession, email, password, image) VALUES(?,?,?,?,?,?)');
                        $insert_tutor->execute([$id, $name, $profession, $email, $pass, $rename]);
                        $message = 'New tutor registered! You can login now';
                    } else {
                        $message = 'Failed to upload image';
                    }
                } else {
                    $message = 'Please select an image';
                }
            }
        }
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
            echo '
            <div class="message">
                <span>'.$message.'</span>
                <i class="bx bx-x" onclick="this.parentElement.remove();"></i>
            </div>
            ';
        }        
        
    ?>
    <div class="form-container">
        <img src="../image/fun.jpg" class="form-img" style="left:-2%">
        <form action="" method="post" enctype="multipart/form-data" class="register">
            <h3>register now</h3>
            <div clas="flex">
                <div class="col">
                    <p>your name<span>*</span></p>
                    <input type="text" name="name" placeholder="enter your name" maxlength="50" required class="box">
                    <p>your profession <span>*</span></p>
                    <select name="profession" required class="box">
                        <option value="" disabled selected>--select your profession--</option>
                        <option value="developer">developer</option>
                        <option value="teacher">teacher</option>
                        <option value="student">student</option> 
                        <option value="engineer">engineer</option>  
                    </select>
                    <p>your email<span>*</span></p>
                    <input type="email" name="email" placeholder="enter your email" maxlength="50" required class="box">
                </div>
                <div class="col">
                    `<p>your password<span>*</span></p>
                    <input type="password" name="pass" placeholder="enter your password" maxlength="50" required class="box">
                    <p>your password<span>*</span></p>
                    <input type="password" name="cpass" placeholder="confirm your password" maxlength="50" required class="box">
                    <p>select pic<span>*</span></p>
                    <input type="file" name="image" accept="image/*" required class="box">`
                </div>
            </div>
            <p class="link">already have an account ? <<a href="login.php">login now</a></p>
                <input type="submit" name="submit" class="btn" value="register now">
        </form>
    </div>
</body>
</html>