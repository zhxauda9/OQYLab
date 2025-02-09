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
    <title>Dashboard</title>
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
            <select name="status">
                <option value="" selected disabled>--select status--</option>
                <option value="active">active</option>
                <option value="deactive">deactive</option>
            </select>
            <p>playlist title <span>*</span></p>
            <input type="text" name="title" maxlength="100" required placeholder="Enter playlist title" class="box">
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
