<?php
include __DIR__ . '/../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    $tutor_id = '';
    // header('location:login.php');
}

// Функция для генерации уникального ID
function generateShortId() {
    return bin2hex(random_bytes(4)); // Создаёт 8-символьный уникальный ID
}

// Функция для генерации короткого имени файла
function generateShortFilename($filename) {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    return uniqid() . bin2hex(random_bytes(4)) . '.' . $ext;
}

if (isset($_POST['submit'])) {
    $id = generateShortId(); // Используем новую функцию
    
    $title = filter_var($_POST['title'], FILTER_SANITIZE_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS);
    $status = filter_var($_POST['status'], FILTER_SANITIZE_SPECIAL_CHARS);

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_SPECIAL_CHARS);
    $rename = generateShortFilename($image);
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = __DIR__ . '/../uploaded_files/' . $rename;
    $uploadDir = __DIR__ . '/../uploaded_files/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($image_tmp_name, $image_folder)) {
        $add_playlist = $conn->prepare('INSERT INTO `playlist` (id, tutor_id, title, description, thumb, status, date) VALUES (?, ?, ?, ?, ?, ?, NOW())');
        $add_playlist->execute([$id, $tutor_id, $title, $description, $rename, $status]);
        echo 'New playlist added';
    } else {
        echo 'Failed to upload image';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OQYLab</title>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
    <?php include __DIR__ . '/../components/admin_header.php'; ?>
    <section class="playlist-form">
        <h1 class="heading">create playlist</h1>

        <form action="" method="post" enctype="multipart/form-data">
            <p>playlist status <span>*</span></p>
            <select name="status" class="box">
                <option value="" selected disabled>--select status--</option>
                <option value="active">active</option>
                <option value="deactive">deactive</option>
            </select>
            <p>playlist title <span>*</span></p>
            <input type="text" name="title" maxlength="150" required placeholder="Enter playlist title" class="box">
            <p>playlist description</p> 
            <textarea name="description" class="box"placeholder="write description" maxlength="1000" cols="30" rows="10"></textarea>
            <p>playlist thumbnail<span>*</span></p>
            <input type="file" name="image" accept="image/*" required class="box">
            <input type="submit" name="submit" value="create playlist" class="btn">
        </form>

    </section>
    <?php include __DIR__ . '/../components/footer.php'; ?>
    <script src="/../js/admin_script.js"></script>
</body>
</html>
