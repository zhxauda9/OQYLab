<?php
include __DIR__ . '/components/connect.php';
  if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
  }else{
    $user_id='';
    // header('location:login.php');
  }
  $verify_user = $conn->prepare('SELECT * FROM `users` WHERE id=?');
  $verify_user->execute([$user_id]);
  $fetch_profile = $verify_user->fetch(PDO::FETCH_ASSOC);

  $select_bookmarks = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id=?");
  $select_bookmarks->execute([$user_id]);
  $total_bookmarks=$select_bookmarks->rowCount();

  $select_likes=$conn->prepare("SELECT * FROM `likes` WHERE user_id=?");
  $select_likes->execute([$user_id]);
  $total_likes=$select_likes->rowCount();

  $select_comments=$conn->prepare(query: "SELECT * FROM `comments` WHERE user_id=?");
  $select_comments->execute([$user_id]);
  $total_comments=$select_comments->rowCount();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/css/user_style.css">
</head>
<body>
    <?php include __DIR__ . '/components/user_header.php'; ?>
    <section class="dashboard">
      <h1 class="heading">dashboard</h1>

      <div class="box-container">
        <div class="box">
          <h3>Welcome!</h3>
          <p><?= $fetch_profile['name'];?></p>
          <a href="#" class="btn">view profile</a>
        </div>
        <div class="box">
          <h3><?= $total_bookmarks;?></h3>
          <p>total bookmarks</p>
          <a href="add_content.php" class="btn">view my bookmarks</a>
        </div>
        <div class="box">
          <h3><?= $total_comments;?></h3>
          <p>total comments</p>
          <nt href="#" class="btn">view my comments</nt>
        </div>
        <div class="box">
          <h3><?= $total_likes;?></h3>
          <p>total likes</p>
          <a href="#" class="btn">view my likes</a>
        </div>
      </div>
    </section>
    <?php include __DIR__ . '/components/footer.php'; ?>
    <script src="/js/admin_script.js"></script>
</body>
</html>
