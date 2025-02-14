<?php
include __DIR__ . '/../components/connect.php';
function generate_unique_id($conn) {
    do {
        $id = substr(sha1(uniqid(mt_rand(), true)), 0, 20);
        $check_id = $conn->prepare("SELECT id FROM `tutors` WHERE id = ?");
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
                    $image = filter_var($image, FILTER_SANITIZE_SPECIAL_CHARS);
                    $rename = generateShortFilename($image);
                    $image_tmp_name = $_FILES['image']['tmp_name'];
                    $image_folder = __DIR__ . '/../uploaded_files/' . $rename;
                    $uploadDir = __DIR__ . '/../uploaded_files/';

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
    <title>OQYLab</title>
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
            <h3>Тіркелу</h3>
            <div clas="flex">
                <div class="col">
                    <p>атыңыз<span>*</span></p>
                    <input type="text" name="name" placeholder="Қайрат" maxlength="50" required class="box">
                    <p>Мамандығыңыз<span>*</span></p>
                    <select name="profession" required class="box">
                        <option value="" disabled selected>--Мамандығыңызды таңдаңыз--</option>
                        <option value="developer">Бағдарламашы</option>
                        <option value="teacher">Мұғалім</option>
                        <option value="student">Студент</option> 
                        <option value="engineer">Инженер</option>  
                    </select>
                    <p>электронды пошта<span>*</span></p>
                    <input type="email" name="email" placeholder="..@gmail.com" maxlength="50" required class="box">
                </div>
                <div class="col">
                    `<p>құпиясөз<span>*</span></p>
                    <input type="password" name="pass" placeholder="..." maxlength="50" required class="box">
                    <p>құпиясөз растаңыз<span>*</span></p>
                    <input type="password" name="cpass" placeholder="..." maxlength="50" required class="box">
                    <p>сурет таңдаңыз<span>*</span></p>
                    <input type="file" name="image" accept="image/*" required class="box">`
                </div>
            </div>
            <p class="link">Аккаунтыңыз бар ма? <<a href="login.php">Кіру</a></p>
                <input type="submit" name="submit" class="btn" value="Тіркелу">
        </form>
    </div>
</body>
</html>