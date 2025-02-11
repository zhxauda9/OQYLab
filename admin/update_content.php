<?php
include __DIR__ . '/../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    $tutor_id = '';
    // header('location:login.php');
}

if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id'];
} else {
    header('location:dashboard.php');
    exit();
}

//upload 

function generateShortFilename($filename) {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    return uniqid() . bin2hex(random_bytes(4)) . '.' . $ext;
}

if (isset($_POST['update'])) {
    $video_id= $_POST['video_id'];
    $video_name= filter_var($video_id,FILTER_SANITIZE_SPECIAL_CHARS);

    $status=$_POST['status'];
    $status=filter_var($status,FILTER_SANITIZE_SPECIAL_CHARS);

    $description=$_POST['description'];
    $description=filter_var($description,FILTER_SANITIZE_SPECIAL_CHARS);

    $title=$_POST['title'];
    $title=filter_var($title,FILTER_SANITIZE_SPECIAL_CHARS);

    $playlist=$_POST['playlist'];
    $playlist=filter_var($playlist,FILTER_SANITIZE_SPECIAL_CHARS);

    $update_content=$conn->prepare("UPDATE `content` SET title=?, description=?, status=? WHERE id=?");
    $update_content->execute([$title,$description,$status,$video_id]);

    if(!empty($playlist)){
        $update_playlist=$conn->prepare("UPDATE `content` SET playlist_id = ? WHERE id = ?");
        $update_playlist->execute([$playlist,$video_id]);
    }

    if (!empty($_FILES['image']['name'])) {
        $old_thumb = $_POST['old_thumb'];

        $image = $_FILES['image']['name'];
        $rename = generateShortFilename($image);
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = __DIR__ . '/../uploaded_files/' . $rename;

        if (move_uploaded_file($image_tmp_name, $image_folder)) {
            if (!empty($old_thumb) && file_exists(__DIR__ . "/../uploaded_files/$old_thumb")) {
                unlink(__DIR__ . "/../uploaded_files/$old_thumb");
            }
            $update_img = $conn->prepare('UPDATE `content` SET thumb=? WHERE id=?');
            $update_img->execute([$rename, $video_id]);
        } 
    }

    if (!empty($_FILES['video']['name'])) {
        $old_video = $_POST['old_video'];

        $video = $_FILES['video']['name'];
        $rename = generateShortFilename($video);
        $video_tmp_name = $_FILES['video']['tmp_name'];
        $video_folder = __DIR__ . '/../uploaded_files/' . $rename;

        if (move_uploaded_file($video_tmp_name, $video_folder)) {
            if (!empty($old_video) && file_exists(__DIR__ . "/../uploaded_files/$old_video")) {
                unlink(__DIR__ . "/../uploaded_files/$old_video");
            }
            $update_img = $conn->prepare('UPDATE `content` SET video=? WHERE id=?');
            $update_img->execute([$rename, $video_id]);
        } 
    }
    $message[]='content updated!';
}

// delete video 

if(isset($_POST['delete_content'])){
    $delete_id=$_POST['video_id'];
    $delete_id=filter_var($delete_id,FILTER_SANITIZE_SPECIAL_CHARS);

    $delete_video_thumb= $conn->prepare('SELECT thumb FROM `content` WHERE id=? LIMIT 1');
    $delete_video_thumb->execute([$delete_id]);
    $fetch_thumb=$delete_video_thumb->fetch(PDO::FETCH_ASSOC);
    if (!empty($fetch_thumb['thumb']) && file_exists(__DIR__ . '/../uploaded_files/'.$fetch_thumb['thumb'])) {
        unlink(__DIR__ . '/../uploaded_files/'.$fetch_thumb['thumb']);
    }

    $delete_video= $conn->prepare('SELECT video FROM `content` WHERE id=? LIMIT 1');
    $delete_video->execute([$delete_id]);
    $fetch_video=$delete_video->fetch(PDO::FETCH_ASSOC);
    if (!empty($fetch_video['video']) && file_exists(__DIR__ . '/../uploaded_files/'.$fetch_video['video'])) {
        unlink(__DIR__ . '/../uploaded_files/'.$fetch_video['video']);
    }

    $delete_likes= $conn->prepare('DELETE FROM `likes` WHERE content_id=?');
    $delete_likes->execute([$delete_id]);
    $delete_comments= $conn->prepare('DELETE FROM `comments` WHERE content_id=?');
    $delete_comments->execute([$delete_id]);

    $delete_content= $conn->prepare('DELETE FROM `content` WHERE id=?');
    $delete_content->execute([$delete_id]);

    $message[]='video deleted';
    header('location:contents.php');
    exit();
}

?>

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
    <div class="video-form">
        <h1 class="heading">upload content</h1>
        <?php
        $select_video=$conn->prepare('SELECT * FROM `content` WHERE id=? AND tutor_id=?');
        $select_video->execute([$get_id,$tutor_id]);

        if($select_video->rowCount()>0){
            while($fetch_video = $select_video->fetch(PDO::FETCH_ASSOC)){
                $video_id=$fetch_video['id'];
        ?>


        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="video_id" value="<?=$fetch_video['id'];?>">
            <input type="hidden" name="old_thumb" value="<?=$fetch_video['thumb'];?>">
            <input type="hidden" name="old_video" value="<?=$fetch_video['video'];?>">
            
            <p>update status<span>*</span></p>
            <select name="status" class="box">
                <option value="<?=$fetch_video['status'];?>"><?=$fetch_video['status'];?></option>
                <option value="active">active</option>
                <option value="deactive">deactive</option>
            </select>
            <p>update title <span>*</span></p>
            <input type="text" name="title" maxlength="150" required placeholder="Enter playlist title" value="<?=$fetch_video['title'];?>" class="box"> 
            <p>update description <span>*</span></p>
            <textarea name="description" class="box" placeholder="write description" maxlength="1000" cols="30" rows="10"><?= $fetch_video['description']; ?></textarea>

            <p>update playlist <span>*</span></p>
            <select name="playlist" class="box" required>
             <option value="<?=$fetch_video['playlist_id'];?>" selected disabled>--select playlist--</option>
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
            <img src="../uploaded_files/<?= $fetch_video['thumb'];?>">
            <p>update thumbnail<span>*</span></p>
            <input type="file" name="image" accept="image/*" class="box">
            <video src="../uploaded_files/<?=$fetch_video['video'];?>" controls></video>
            <p>update video<span>*</span></p>
            <input type="file" name="video" accept="video/*" class="box">
            <div class="flex-btn">
            <input type="submit" name="update" value="update video" class="btn">
            <a href="view_content.php?get_id=<?=$video_id;?>" class="btn">view content</a>
            <input type="submit" name="delete_content" value="delete video" class="btn">
            </div>
        </form>
    <?php
            }
        }else{
            echo '
                </div>
                    <div class="empty">
                    <p style="margin-bottom: 1.5rem;"> no video added yet!</p>
                    <a href="add_content.php" class="btn" style="margin-top:1.5rem">add videos</a>
                    </div>
                ';
        }
    ?>
    </div>
    <?php include __DIR__ . '/../components/footer.php'; ?>
    <script src="/../js/admin_script.js"></script>
</body>
</html>
