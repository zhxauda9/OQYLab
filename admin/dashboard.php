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
    <section class="dashboard">
      <h1 class="heading">профиль</h1>

      <div class="box-container">
        <div class="box">
          <h3>Қош келдіңіз!</h3>
          <p><?= $fetch_profile['name'];?></p>
          <a href="profile.php" class="btn">профиль тексеру</a>
        </div>
        <div class="box">
          <h3><?= $total_contents;?></h3>
          <p>барлық сабақтар</p>
          <a href="add_content.php" class="btn">сабақ қосу</a>
        </div>
        <div class="box">
          <h3><?= $total_playlists;?></h3>
          <p>барлық курстар</p>
          <a href="add_playlist.php" class="btn">курс қосу</a>
        </div>
        <div class="box">
          <h3><?= $total_likes;?></h3>
          <p>барлық лайк</p>
          <a href="contents.php" class="btn">сабақтар қарау</a>
        </div>
        <div class="box">
          <h3><?= $total_comments;?></h3>
          <p>барлық пікірлер</p>
          <a href="comments.php" class="btn">пікірлер қарау</a>
        </div>
        <div class="box">
          <h3>тез қолдау</h3>
          <div class="flex-btn">
            <a href="login.php" class="btn" style="width:200px;">кіру</a>
            <a href="register.php" class="btn" style="width:200px;">тіркелу</a>
          </div>"
        </div>
      </div>
    </section>
    <?php include __DIR__ . '/../components/footer.php'; ?>
    <script src="/../js/admin_script.js"></script>
</body>
</html>
