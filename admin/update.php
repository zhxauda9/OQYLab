<?php
include __DIR__ . '/../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    $tutor_id = '';
}

$message = []; // Инициализация массива сообщений

if (isset($_POST['submit'])) {
    $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id=? LIMIT 1");
    $select_tutor->execute([$tutor_id]);
    $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);

    $prev_pass = $fetch_tutor["password"];
    $prev_image = $fetch_tutor["image"];

    $name = filter_var($_POST["name"], FILTER_SANITIZE_SPECIAL_CHARS);
    $profession = filter_var($_POST['profession'], FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!empty($name)) {
        $update_name = $conn->prepare('UPDATE `tutors` SET name=? WHERE id=?');
        $update_name->execute([$name, $tutor_id]);
        $message[] = 'Username updated successfully';
    }

    if (!empty($profession)) {
        $update_profession = $conn->prepare('UPDATE `tutors` SET profession=? WHERE id=?');
        $update_profession->execute([$profession, $tutor_id]);
        $message[] = 'User profession updated successfully';
    }

    if (!empty($email)) {
        $select_email = $conn->prepare('SELECT * FROM `tutors` WHERE email=?');
        $select_email->execute([$email]);
        if ($select_email->rowCount() > 0) {
            $message[] = 'Email already taken';
        } else {
            $update_email = $conn->prepare('UPDATE `tutors` SET email=? WHERE id=?');
            $update_email->execute([$email, $tutor_id]);
            $message[] = 'User email updated successfully';
        }
    }

    // Работа с изображениями
    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_SPECIAL_CHARS);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = uniqid() . '.' . $ext;
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_files/' . $rename;

    if (!empty($image)) {
        if ($image_size > 200000) {
            $message[] = 'Image size too large';
        } else {
            move_uploaded_file($image_tmp_name, $image_folder);
            $update_image = $conn->prepare('UPDATE `tutors` SET `image`=? WHERE id=?');
            $update_image->execute([$rename, $tutor_id]);
            if (!empty($prev_image) && $prev_image != $rename) {
                unlink('../uploaded_files/' . $prev_image);
            }
            $message[] = 'Image updated successfully';
        }
    }

    $empty_pass = sha1('dummy_value');
    $old_pass = sha1($_POST['old_pass']);
    $new_pass = sha1($_POST['new_pass']);
    $cpass = sha1($_POST['cpass']);

    if ($old_pass != $empty_pass) {
        if ($old_pass != $prev_pass) {
            $message[] = 'Old password not matched';
        } elseif ($new_pass != $cpass) {
            $message[] = 'Confirm password not matched';
        } else {
            if ($new_pass != $empty_pass) {
                $update_pass = $conn->prepare("UPDATE `tutors` SET password=? WHERE id=?");
                $update_pass->execute([$new_pass, $tutor_id]);
                $message[] = "Password updated successfully";
            } else {
                $message[] = "Please enter a new password";
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
<?php include __DIR__ . '/../components/admin_header.php'; ?>
    <div class="form-container" style="min-height: calc(100vh - 19rem);">
        <form action="" method="post" enctype="multipart/form-data" class="register">
            <h3>update profile</h3>
            <div clas="flex">
                <div class="col">
                    <p>your name<span>*</span></p>
                    <input type="text" name="name" placeholder="<?= $fetch_profile['name']; ?>" maxlength="50" required class="box">
                    <p>your profession <span>*</span></p>
                    <select name="profession" required class="box">
                        <option value="" disabled selected><?= $fetch_profile['profession']; ?></option>
                        <option value="developer">developer</option>
                        <option value="teacher">teacher</option>
                        <option value="student">student</option> 
                        <option value="engineer">engineer</option>  
                    </select>
                    <p>your email<span>*</span></p>
                    <input type="email" name="email" placeholder="<?= $fetch_profile['email']; ?>" maxlength="50" required class="box">
                </div>
                <div class="col">
                    `<p>old password<span>*</span></p>
                    <input type="password" name="old_pass" placeholder="enter your old password" maxlength="50" required class="box">
                    <p>new password<span>*</span></p>
                    <input type="password" name="new_pass" placeholder="enter your new password" maxlength="50" required class="box">
                    <p>confirm new password<span>*</span></p>
                    <input type="password" name="cpass" placeholder="confirm your new password" maxlength="50" required class="box">
                </div>
            </div>
            <p>update pic<span>*</span></p>
            <input type="file" name="image" accept="image/*" required class="box">`
            <input type="submit" name="submit" class="btn" value="update profile">
        </form>
    </div>
    <?php include __DIR__ . '/../components/footer.php'; ?>
    <script src="/../js/admin_script.js"></script>
</body>
</html>