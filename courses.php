<?php
    include __DIR__ . '/components/connect.php';

    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    } else {
        $user_id = '';
    }

    $select_likes=$conn->prepare('SELECT * FROM `likes` WHERE user_id=?');
    $select_likes->execute([$user_id]);
    $total_likes=$select_likes->rowCount();

    $select_comments=$conn->prepare('SELECT * FROM `comments` WHERE user_id=?');
    $select_comments->execute([$user_id]);
    $total_comments=$select_comments->rowCount();

    $select_bookmark=$conn->prepare('SELECT * FROM `bookmark` WHERE user_id=?');
    $select_bookmark->execute([$user_id]);
    $total_bookmark=$select_bookmark->rowCount();

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
    <?php include 'components/user_header.php'; ?>
    <div class="courses">
        <div class="heading">
            <span>top popular course</span>
            <h1>OqyLab course student<br> can join with us</h1>
        </div>
        <div class="box-container">
            <?php
            $select_courses=$conn->prepare('SELECT * FROM `playlist` WHERE status=? ORDER BY date');
            $select_courses->execute(['active']);
            if($select_courses->rowCount()> 0){
            while($fetch_courses=$select_courses->fetch(PDO::FETCH_ASSOC)) {
                $course_id=$fetch_courses['id'];

                $select_tutor=$conn->prepare('SELECT * FROM `tutors` WHERE id=?');
                $select_tutor->execute([$fetch_courses['tutor_id']]);
                $fetch_tutor=$select_tutor->fetch(PDO::FETCH_ASSOC);
            ?>
            <div class="box">
            <div class="tutor">
                <img src="uploaded_files/<?=$fetch_tutor['image'];?>">
                <div>
                <h3><?= $fetch_tutor['name'];?></h3>
                <span><?= $fetch_courses['date'];?></span>
                </div>
            </div>
            <img class="thumb" src="uploaded_files/<?= $fetch_courses['thumb'];?>">
            <h3 class="title"><?= $fetch_courses['title'];?></h3>
            <a href="playlist.php?get_id=<?= $course_id;?>" class="btn">view playlist</a>
            </div>
            <?php
            }
        }else{
            echo '<p class="empty">no courses added yet</p>';
        }
        ?>
        </div>
        </div>
    <?php include __DIR__ . '/components/footer.php'; ?>
    <script src="/../js/user_script.js"></script>
</body>
</html>