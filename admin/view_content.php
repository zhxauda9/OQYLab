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
    header('location:contents.php');
    exit();
}

//delete video from playlist

if(isset($_POST['delete_video'])) {
    $delete_id = $_POST['video_id'];
    $delete_id=filter_var($delete_id, FILTER_SANITIZE_SPECIAL_CHARS);

    $verify_video=$conn->prepare('SELECT * FROM `content` WHERE id=? LIMIT 1');
    $verify_video->execute([$delete_id]);

    if( $verify_video->rowCount() > 0) {
        $delete_video_thumb=$conn->prepare('SELECT * FROM `content` WHERE id=? LIMIT 1');
        $delete_video_thumb->execute([$delete_id]);
        $fetch_thumb=$delete_video_thumb->fetch(PDO::FETCH_ASSOC);
        unlink(__DIR__ . '/../uploaded_files/'.$fetch_thumb['thumb']);

        $delete_video=$conn->prepare('SELECT * FROM `content` WHERE id=? LIMIT 1');
        $delete_video->execute([$delete_id]);
        $fetch_video=$delete_video->fetch(PDO::FETCH_ASSOC);
        unlink(__DIR__ . '/../uploaded_files/'.$fetch_video['video']);

        $delete_likes=$conn->prepare('SELECT * FROM `likes` WHERE content_id=?');
        $delete_likes->execute([$delete_id]);

        $delete_comments= $conn->prepare('SELECT * FROM `comments` WHERE content_id=?');
        $delete_comments->execute([$delete_id]);

        $delete_content=$conn->prepare("DELETE FROM `content` WHERE id=?");
        $delete_content->execute([$delete_id]);

        $message[]='video deleted';
    }else{
        $message[]= 'video already deleted';
    }
}

//delet comment from content

if(isset($_POST['delete_comment'])) {
    $delete_id=$_POST['delete_id'];
    $delete_id=filter_var($delete_id,FILTER_SANITIZE_SPECIAL_CHARS);

    $verify_comment=$conn->prepare('SELECT * FROM `comments` WHERE id=? LIMIT 1');
    $verify_comment->execute([$delete_id]);

    if($verify_comment->rowCount()>0){
        $delete_comment=$conn->prepare("DELETE FROM `comments` WHERE id=?");
        $delete_comment->execute([$delete_id]);
        $message[]='comment deleted successfully!';
    }else{
        $message[]='comment already deleted';
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
    <section class="view-content">
        <h1 class="heading">content detail</h1>

        <?php
            $select_content=$conn->prepare("SELECT * FROM `content` WHERE id=? AND tutor_id=?");
            $select_content->execute([$get_id,$tutor_id]);

            if($select_content->rowCount() > 0) {
                while($fetch_content=$select_content->fetch(PDO::FETCH_ASSOC)) {
                    $video_id=$fetch_content['id'];

                    $count_likes=$conn->prepare("SELECT * FROM `likes` WHERE tutor_id=? AND content_id=?");
                    $count_likes->execute([$tutor_id,$video_id]);
                    $total_likes=$count_likes->rowCount();

                    $count_comments=$conn->prepare("SELECT * FROM `comments` WHERE tutor_id=? AND content_id=?");
                    $count_comments->execute([$tutor_id,$video_id]);
                    $total_comments=$count_comments->rowCount();
        ?>
        <div class="container">
            <video src="../uploaded_files/<?=$fetch_content['video'];?>" autoplay controls posters="../uploaded_files/<?=$fetch_content['thumb'];?>" class="video"></video>
            <div class="date"><i class="bx bxs-calendar-alt"></i><span><?= $fetch_content['date'];?></span></div>
            <h3 class="title"><?= $fetch_content['title'];?></h3>
            <div class="flex">
                <div><i class='bx bxs-heart'></i><span><?= $total_likes;?></span></div>
                <div><i class='bx bxs-chat'></i><span><?= $total_comments;?></span></div>
            </div>
            <div class="description">
                <?= $fetch_content['description'];?>
            </div>
            <form action="" method="post">
                <input type="hidden" name="video_id" value="<?= $video_id;?>">
                <a href="update_content.php?get_id=<?=$video_id;?>" class="btn">update</a>
                <input type="submit" name="delete_video" value="delete video" class="btn" onclick="return confirm('delete this video?');">
            </form>
        </div>
        <?php
                }
            }else{
                echo '
                </div>
                    <div class="empty">
                    <p style="margin-bottom: 1.5rem;"> no content added yet!</p>
                    <a href="add_content.php" class="btn" style="margin-top:1.5rem">add contents</a>
                    </div>
                ';
            }
        ?>

    </section>
    <section class="comments">
            <h1 class="heading">user comments</h1>
            <div class="show comments">
                <?php
                $select_comments=$conn->prepare("SELECt * FROM `comments` WHERE content_id=?");
                $select_comments->execute([$get_id]);
                if($select_comments->rowCount()>0){
                    while($fetch_comment=$select_comments->fetch(PDO::FETCH_ASSOC)){
                        $select_commentor=$conn->prepare('SELECT * FROM `users` WHERE id=?');
                        $select_commentor->execute([$fetch_comment['user_id']]);
                        $fetch_commentator=$select_commentor->fetch(PDO::FETCH_ASSOC);
                    
                ?>
                <div class="box">
                    <div class="user">
                        <img src="../uploaded_files/<?=$fetch_commentator['image'];?>">
                        <div>
                            <h3><?=$fetch_commentator['name'];?></h3>
                            <span><?=$fetch_comment['date'];?></span>
                        </div>
                    </div>
                    <p class="text"><?=$fetch_comment['comment'];?></p>
                    <form action="" method="post" class="flex-btn">
                        <input type="hidden" name="comment_id" value="<?=$fetch_comment['id'];?>">
                        <button type="submit" name="delete_comment" value="delete comment" class="btn"
                        onclick="return confirm('delete this comment');">delete comment</button>
                    </form>
                </div>
                <?php
                    }
                }
                echo '<p class="empty">no comments added yet!</p>'
                ?>
            </div>
    </section>
    <?php include __DIR__ . '/../components/footer.php'; ?>
    <script src="/../js/admin_script.js"></script>
</body>
</html>
