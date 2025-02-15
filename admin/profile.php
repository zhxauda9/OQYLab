<?php
include __DIR__ . '/../components/connect.php';
  if(isset($_COOKIE['tutor_id'])){
    $tutor_id = $_COOKIE['tutor_id'];
  }else{
    $tutor_id='';
    // header('location:login.php');
  }

  $select_contents=$conn->prepare("SELECT * FROM `content` WHERE tutor_id=?");
  $select_contents->execute([$tutor_id]);
  $total_contents=$select_contents->rowCount();

  $select_playlists=$conn->prepare("SELECT * FROM `playlist` WHERE tutor_id=?");
  $select_playlists->execute([$tutor_id]);
  $total_playlists=$select_playlists->rowCount();

  $select_likes=$conn->prepare("SELECT * FROM `likes` WHERE tutor_id=?");
  $select_likes->execute([$tutor_id]);
  $total_likes=$select_likes->rowCount();

  $select_comments=$conn->prepare(query: "SELECT * FROM `comments` WHERE tutor_id=?");
  $select_comments->execute([$tutor_id]);
  $total_comments=$select_comments->rowCount();
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
    <section class="tutor-profile" style="min-height: calc(100vh - 19rem);">
      <h1 class="heading">Профиль</h1>
        <div class="details">
            <div class="tutor">
            <img src="../uploaded_files/<?= $fetch_profile['image'];?>">
                <h3><?= $fetch_profile['name'];?></h3>
                <span><?=$fetch_profile['profession'];?></span>
                <a href="update.php" class="btn">профиль жаңарту</a>
            </div>
            <div class="flex">
                <div class="box">
                    <span><?= $total_playlists;?></span>
                    <p>барлық курстар</p>
                    <a href="playlists.php" class="btn">курстар қарау</a>
                </div>
                <div class="box">
                    <span><?= $total_contents;?></span>
                    <p>барлық сабақтар</p>
                    <a href="contents.php" class="btn">сабақтар қарау</a>
                </div>
                <div class="box">
                    <span><?= $total_likes;?></span>
                    <p>барлық лайктар</p>
                    <a href="likes.php" class="btn">лайктар қарау</a>
                </div>
                <div class="box">
                    <span><?= $total_comments;?></span>
                    <p>барлық пікірлер</p>
                    <a href="comments.php" class="btn">пікірлер қарау</a>
                </div>
            </div>
        </div>
    </section>
    <?php include __DIR__ . '/../components/footer.php'; ?>
    <script src="/../js/admin_script.js"></script>
</body>
</html>
