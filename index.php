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
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT-Әлемі</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<?php include __DIR__ . '/components/user_header.php'; ?>

<section class="section-1">
  <div class="video-background">
    <div class="video-wrapper">
      <iframe 
        src="https://www.youtube.com/embed/9HaU8NjH7bI?autoplay=1&mute=1&loop=1&playlist=9HaU8NjH7bI&controls=0&showinfo=0&modestbranding=1&rel=0&iv_load_policy=3"
        frameborder="0" allowfullscreen allow="autoplay; encrypted-media; picture-in-picture">
      </iframe>
    </div>
    <div class="overlay"></div>
    <div class="content">
      <h1>IT-Әлемі</h1>
      <p>Қазақ тілінде IT-курстарды үйреніп, болашағыңызды жасаңыз!</p>
      <a href="courses.php" class="btn" href="#">Бастау</a>
    </div>
  </div>
</section>

<div class="categories">
  <div class="heading">
    <span>categories</span>
    <h1>explore  top courses categories<br>that change yourself</h1>
  </div>
  <div class="box-container">
    <div class="box">
      <img src="image/server.png">
      <h3>IT and software</h3>
      <a href="courses.php">13 courses <i class="bx bx-right-arrow-alt"></i></a>
    </div>
    <div class="box">
      <img src="image/web-design.png">
      <h3>web design</h3>
      <a href="courses.php">15 courses <i class="bx bx-right-arrow-alt"></i></a>
    </div>
    <div class="box">
      <img src="image/smartphone.png">
      <h3>mobile application</h3>
      <a href="courses.php">14 courses <i class="bx bx-right-arrow-alt"></i></a>
    </div>
    <div class="box">
      <img src="image/design.png">
      <h3>graph design</h3>
      <a href="courses.php">9 courses <i class="bx bx-right-arrow-alt"></i></a>
    </div>
    <div class="box">
      <img src="image/server.png">
      <h3>computer math</h3>
      <a href="courses.php">6 courses <i class="bx bx-right-arrow-alt"></i></a>
    </div>
    <div class="box">
      <img src="image/server.png">
      <h3>IT and software</h3>
      <a href="courses.php">13 courses <i class="bx bx-right-arrow-alt"></i></a>
    </div>
  </div>
</div>

<!------ courses selection ---- -->
<div class="courses">
  <div class="heading">
    <span>top popular course</span>
    <h1>OqyLab course student<br> can join with us</h1>
  </div>
  <div class="box-container">
    <?php
    $select_courses=$conn->prepare('SELECT * FROM `playlist` WHERE status=? ORDER BY date DESC LIMIT 6');
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
        <img src="uploaded_files/<?=$fetch_courses['thumb'];?>">
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
  <div class="more-btn">
    <a href="courses.php" class="btn">view more</a>
  </div>
</div>

<!--- benefits -- -->
<div class="benifites">
  <img src="image/map.png">
  <div class="detail">
    <h1>Globally trustes by hundreds of <br> thousands of customers.</h1>
    <p>Work Smarter Create Better Build Faster</p>
    <a href="courses.php" class="btn">explore courses now</a>
    <p>HOW WILL OQYLAB BENFIT ME?</p>
    <div class="box-container">
      <div class="box">
        <img src="image/benefit-01.png">
        <p>Free Lifetime <br>Update </p>
      </div>
      <div class="box">
        <img src="image/benefit-02.png">
        <p>Premium Support <br>6 Months Free</p>
      </div>
      <div class="box">
        <img src="image/benefit-03.png">
        <p>High Speed<br>Perfomance</p>
      </div>
      <div class="box">
        <img src="image/benefit-04.png">
        <p>We Provided Premium<br>Courses </p>
      </div>
      <div class="box">
        <img src="image/benefit-05.png">
        <p>Developer Friendly<br>Code & Design</p>
      </div>
    </div>
  </div>
</div>

<div class="teacher-section">
  <div class="heading">
    <span>our teacher</span>
    <h1>whose inspiration you</h1>
  </div>
  <div class="box-container">
    <div class="teacher-tabs">
      <img src="image/team-01.jpg" class="tab-item active" data-target="#team-01">
      <img src="image/team-02.jpg" class="tab-item" data-target="#team-02">
      <img src="image/team-03.jpg" class="tab-item" data-target="#team-03">
      <img src="image/team-04.jpg" class="tab-item" data-target="#team-04">
      <img src="image/team-05.jpg" class="tab-item" data-target="#team-05">
      <img src="image/team-06.jpg" class="tab-item" data-target="#team-06">
    </div>
  </div>
  <div class="tab-content" id="team-01">
    <img src="image/team-01.jpg">
    <div class="detail">
      <h2>Manes Mary</h2>
      <span>English teacher</span>
      <p><i class="bx bx-location-plus"></i>CO Miergo, AD, USA</p>
      <p>kfjdksjfjsdjfsjkjsjdkjsfjsdljfklsdf kkll lk k lk lk k k kl kl k l</p>
      <div class="icons">
        <i class="bx bxl-instagram"></i>
        <i class="bx bxl-twitter"></i>
        <i class="bx bxl-facebook"></i>
        <i class="bx bxl-linkedin"></i>
      </div>
      <a href=""><i class="bx bx-phone"></i>+1-202-555-0174</a>
      <a href=""><i class="bx bx-envelope"></i>example@gmail.com</a>
    </div>
  </div>
  <div class="tab-content" id="team-02">
    <img src="image/team-02.jpg">
    <div class="detail">
      <h2>Jasmin jdskaj</h2>
      <span>English teacher</span>
      <p><i class="bx bx-location-plus"></i>CO Miergo, AD, USA</p>
      <p>kfjdksjfjsdjfsjkjsjdkjsfjsdljfklsdf kkll lk k lk lk k k kl kl k l</p>
      <div class="icons">
        <i class="bx bxl-instagram"></i>
        <i class="bx bxl-twitter"></i>
        <i class="bx bxl-facebook"></i>
        <i class="bx bxl-linkedin"></i>
      </div>
      <a href=""><i class="bx bx-phone"></i>+1-202-555-0174</a>
      <a href=""><i class="bx bx-envelope"></i>example@gmail.com</a>
    </div>
  </div>
  <div class="tab-content" id="team-03">
    <img src="image/team-03.jpg">
    <div class="detail">
      <h2>ldfkdls;kd</h2>
      <span>English teacher</span>
      <p><i class="bx bx-location-plus"></i>CO Miergo, AD, USA</p>
      <p>kfjdksjfjsdjfsjkjsjdkjsfjsdljfklsdf kkll lk k lk lk k k kl kl k l</p>
      <div class="icons">
        <i class="bx bxl-instagram"></i>
        <i class="bx bxl-twitter"></i>
        <i class="bx bxl-facebook"></i>
        <i class="bx bxl-linkedin"></i>
      </div>
      <a href=""><i class="bx bx-phone"></i>+1-202-555-0174</a>
      <a href=""><i class="bx bx-envelope"></i>example@gmail.com</a>
    </div>
  </div>
  <div class="tab-content" id="team-04">
    <img src="image/team-04.jpg">
    <div class="detail">
      <h2>Manes Mary</h2>
      <span>English teacher</span>
      <p><i class="bx bx-location-plus"></i>CO Miergo, AD, USA</p>
      <p>kfjdksjfjsdjfsjkjsjdkjsfjsdljfklsdf kkll lk k lk lk k k kl kl k l</p>
      <div class="icons">
        <i class="bx bxl-instagram"></i>
        <i class="bx bxl-twitter"></i>
        <i class="bx bxl-facebook"></i>
        <i class="bx bxl-linkedin"></i>
      </div>
      <a href=""><i class="bx bx-phone"></i>+1-202-555-0174</a>
      <a href=""><i class="bx bx-envelope"></i>example@gmail.com</a>
    </div>
  </div>
  <div class="tab-content" id="team-05">
    <img src="image/team-05.jpg">
    <div class="detail">
      <h2>Manes Mary</h2>
      <span>English teacher</span>
      <p><i class="bx bx-location-plus"></i>CO Miergo, AD, USA</p>
      <p>kfjdksjfjsdjfsjkjsjdkjsfjsdljfklsdf kkll lk k lk lk k k kl kl k l</p>
      <div class="icons">
        <i class="bx bxl-instagram"></i>
        <i class="bx bxl-twitter"></i>
        <i class="bx bxl-facebook"></i>
        <i class="bx bxl-linkedin"></i>
      </div>
      <a href=""><i class="bx bx-phone"></i>+1-202-555-0174</a>
      <a href=""><i class="bx bx-envelope"></i>example@gmail.com</a>
    </div>
  </div>
  <div class="tab-content" id="team-06">
    <img src="image/team-06.jpg">
    <div class="detail">
      <h2>Manes Mary</h2>
      <span>English teacher</span>
      <p><i class="bx bx-location-plus"></i>CO Miergo, AD, USA</p>
      <p>kfjdksjfjsdjfsjkjsjdkjsfjsdljfklsdf kkll lk k lk lk k k kl kl k l</p>
      <div class="icons">
        <i class="bx bxl-instagram"></i>
        <i class="bx bxl-twitter"></i>
        <i class="bx bxl-facebook"></i>
        <i class="bx bxl-linkedin"></i>
      </div>
      <a href=""><i class="bx bx-phone"></i>+1-202-555-0174</a>
      <a href=""><i class="bx bx-envelope"></i>example@gmail.com</a>
    </div>
  </div>
</div>

<?php include __DIR__ . '/components/footer.php'; ?>
<script src="/../js/user_script.js"></script>
</body>
</html>
