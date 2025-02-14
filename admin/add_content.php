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
    $playlist=$_POST['playlist'];
    $playlist=filter_var($playlist,FILTER_SANITIZE_SPECIAL_CHARS);

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_SPECIAL_CHARS);
    $rename = generateShortFilename($image);
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = __DIR__ . '/../uploaded_files/' . $rename;
    $uploadDir = __DIR__ . '/../uploaded_files/';

    $video = $_FILES['video']['name'];
    $video = filter_var($video, FILTER_SANITIZE_SPECIAL_CHARS);
    $video_rename=generateShortFilename($video);
    $video_tmp_name = $_FILES['video']['tmp_name'];
    $video_folder = __DIR__ . '/../uploaded_files/' . $video_rename;
    $uploadDir = __DIR__ . '/../uploaded_files/';


    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if ((move_uploaded_file($image_tmp_name, $image_folder))&&(move_uploaded_file($video_tmp_name, $video_folder))  ){
        $add_playlist = $conn->prepare('INSERT INTO `content` (id, tutor_id, playlist_id, title, description, video, thumb,date,status) VALUES (?, ?, ?,?,?,?,?,NOW(),?)');
        $add_playlist->execute([$id, $tutor_id, $playlist, $title, $description, $video_rename,$rename,$status]);
        echo 'New content added';
    } else {
        echo 'Failed to upload content';
    }
}
?>

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
    <section class="video-form">
        <h1 class="heading">upload content</h1>

        <form action="" method="post" enctype="multipart/form-data">
            <p>playlist status <span>*</span></p>
            <select name="status" class="box">
                <option value="" selected disabled>--select status--</option>
                <option value="active">active</option>
                <option value="deactive">deactive</option>
            </select>
            <p>video title <span>*</span></p>
            <input type="text" name="title" maxlength="150" required placeholder="Enter playlist title" class="box">
            <p>video description <span>*</span></p>
            <textarea name="description" class="box"placeholder="write description" maxlength="1000" cols="30" rows="10"></textarea>
            <p>video playlist <span>*</span></p>
            <select name="playlist" class="box" required>
             <option value="" selected disabled>--select playlist--</option>
             <?php
             $select_playlists=$conn->prepare('SELECT * FROM `playlist` WHERE tutor_id=?');
             $select_playlists->execute([$tutor_id]);
             if($select_playlists->rowCount()>0){
                while($playlists=$select_playlists->fetch(PDO::FETCH_ASSOC)){
             ?>
                    <option value="<?=$playlists['id'];?>"><?=$playlists['title'];?></option>
             <?php
                } 
                }else{
                    echo '<p class="empty">no playlist added yet!</p>';
                }
             ?>
            </select>
            <p>select thumbnail<span>*</span></p>
            <input type="file" name="image" accept="image/*" required class="box">
            <p>select video<span>*</span></p>
            <input type="file" name="video" accept="video/*" required class="box">
            <input type="submit" name="submit" value="upload video" class="btn">
        </form>

    </section>
    <?php include __DIR__ . '/../components/footer.php'; ?>
    <script src="/../js/admin_script.js"></script>
</body>
</html>
