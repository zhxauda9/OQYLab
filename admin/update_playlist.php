<?php
include __DIR__ . '/../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    $tutor_id = '';
}

if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id'];
} else {
    header('location:playlist.php');
    exit();
}

// Функция для генерации короткого имени файла
function generateShortFilename($filename) {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    return uniqid() . bin2hex(random_bytes(4)) . '.' . $ext;
}

// Выборка данных плейлиста
$select_playlist = $conn->prepare('SELECT * FROM `playlist` WHERE id=?');
$select_playlist->execute([$get_id]);
if ($select_playlist->rowCount() > 0) {
    $fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC);
    $playlist_id = $fetch_playlist['id'];
    $total_videos = $conn->prepare('SELECT COUNT(*) FROM `content` WHERE playlist_id=?');
    $total_videos->execute([$playlist_id]);
    $total_videos = $total_videos->fetchColumn();
} else {
    echo '<p class="empty">No playlist found!</p>';
    exit();
}

// Обновление данных плейлиста
if (isset($_POST['submit'])) {
    $title = filter_var($_POST['title'], FILTER_SANITIZE_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS);
    $status = filter_var($_POST['status'], FILTER_SANITIZE_SPECIAL_CHARS);

    $update_playlist = $conn->prepare("UPDATE `playlist` SET title=?, description=?, status=? WHERE id=?");
    $update_playlist->execute([$title, $description, $status, $get_id]);

    // Обновление изображения
    if (!empty($_FILES['image']['name'])) {
        $old_image = $_POST['old_image'];

        // Загружаем новое изображение
        $image = $_FILES['image']['name'];
        $rename = generateShortFilename($image);
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = __DIR__ . '/../uploaded_files/' . $rename;

        if (move_uploaded_file($image_tmp_name, $image_folder)) {
            // Удаляем старое изображение
            if (!empty($old_image) && file_exists(__DIR__ . "/../uploaded_files/$old_image")) {
                unlink(__DIR__ . "/../uploaded_files/$old_image");
            }
            $update_img = $conn->prepare('UPDATE `playlist` SET thumb=? WHERE id=?');
            $update_img->execute([$rename, $get_id]);
            echo 'Playlist updated!';
        } else {
            echo 'Failed to upload image!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Playlist</title>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
    <?php include __DIR__ . '/../components/admin_header.php'; ?>
    <section class="playlist-form">
        <h1 class="heading">update playlist</h1>
        <?php
        $select_playlist=$conn->prepare('SELECT * FROM `playlist` WHERE id=?');
        $select_playlist->execute([$get_id]);
        if($select_playlist->rowCount()> 0){
            while($fetch_playlist=$select_playlist->fetch(PDO::FETCH_ASSOC)){
                $playlist_id=$fetch_playlist['id'];
                $count_videos=$conn->prepare('SELECT * FROM `content` WHERE playlist_id=?');
                $count_videos->execute([$fetch_playlist['id']]);
                $total_videos=$count_videos->rowCount();
        ?>

        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="old_image" value="<?= $fetch_playlist['thumb'];?>">
            <p>playlist status <span>*</span></p>
            <select name="status" class="box">
                <option value="<?= $fetch_playlist['status'];?>">--select status--</option>
                <option value="active">active</option>
                <option value="deactive">deactive</option>
            </select>
            <p>playlist title <span>*</span></p>
            <input type="text" name="title" maxlength="150" required placeholder="<?= $fetch_playlist['title'];?>" class="box">
            <p>playlist description</p> 
            <textarea name="description" class="box"placeholder="<?= $fetch_playlist['description'];?>" maxlength="1000" cols="30" rows="10"></textarea>
            <p>playlist thumbnail<span>*</span></p>
            <div class="thumb">
                <span><?= $total_videos;?></span>
                <img src="../uploaded_files/<?= $fetch_playlist['thumb'];?>">
            </div>
            <input type="file" name="image" accept="image/*" required class="box">
            <div class="flex-btn">
                <input type="submit" name="submit" value="update playlist" class="btn">
                <a href="view-playlists.php?get_id=<?= $playlist_id;?>" class="btn">view playlist</a>
            </div>
        </form>
        <?php
            }
            }else{
                echo '<p class="empty">no playlist added yet!</p>';
            }
        ?>

    </section>
    <?php include __DIR__ . '/../components/footer.php'; ?>
    <script src="/../js/admin_script.js"></script>
</body>
</html>
