<?php
include __DIR__ . '/components/connect.php';

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
    <?php include __DIR__ . '/components/user_header.php'; ?>
    <section class="view-playlist">
        <h1 class="heading">Курс туралы ақпарат</h1>

        <?php
        $select_playlist=$conn->prepare('SELECT * FROM `playlist` WHERE id=?');
        $select_playlist->execute([$get_id]);
        if($select_playlist->rowCount() > 0){
            while($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)){
                $playlist_id = $fetch_playlist['id'];
                $count_videos = $conn->prepare('SELECT * FROM `content` WHERE playlist_id=?');
                $count_videos->execute([$playlist_id]);
                $total_videos = $count_videos->rowCount();        
        ?>
        <div class="row">
            <div class="thumb">
                <span><?=$total_videos;?></span>
                <img src="../uploaded_files/<?=$fetch_playlist['thumb'];?>">
            </div>
            <div class="details">
                <h3 class="title"><?= $fetch_playlist['title'];?></h3>
                <div class="date"><i class="bx bxs-calendar-alt"></i><span><?=$fetch_playlist['date'];?></span></div>
                <div class="description">
                <?=$fetch_playlist['description'];?>
            </div>
            </div>
        </div>
        <?php
            }
        }else{
            echo '<p class="empty">Курс әлі қосылмады!</p>';
        }
        ?>
    </section>

    <section class="contents">
        <h1 class="heading">Курс сабақтары</h1>

        <div class="box-container">
            <?php
            $select_videos=$conn->prepare("SELECT * FROM `content` WHERE playlist_id=?");
            $select_videos->execute([$playlist_id]);

            if($select_videos->rowCount()> 0) {
                while($fetch_videos = $select_videos->fetch(PDO::FETCH_ASSOC)) {
                    $video_id=$fetch_videos['id'];
            ?>
            <div class="box">
                <div class="flex">
                    <div><i class="bx bx-dots-vertical-rounded" style="<?php if($fetch_videos['status']=='active'){echo 'color:limegreen';}else{echo 'color:red';} ?>"></i>
                        <span style="<?php if($fetch_videos['status']=='active'){echo 'color:limegreen';}else{echo 'color:red';} ?>"></span></div>
                    <div><i class="bx bxs-calendar-alt"></i><span><?=$fetch_videos['date'];?></span></div>
                </div>
                <img src="/uploaded_files/<?=$fetch_videos['thumb'];?>" class="thumb">
                <h3 class="title"><?= $fetch_videos['title'];?></h3>
                <form action="" method="post" class="flex-btn">
                    <a href="content.php?get_id=<?=$video_id;?>" class="btn">Бастау</a>
                </form>
            </div>
            <?php
                }
            } else {
                echo '
                <p class="empty">Сабақтар салынбаған</p>
                ';
            }
            ?>

        </div>
    </section> 
    <?php include __DIR__ . '/components/footer.php'; ?>
    <script src="/js/admin_script.js"></script>
</body>
</html>
