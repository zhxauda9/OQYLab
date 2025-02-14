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
    <title>OQYLab</title>
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
      <h1>OQYlab</h1>
      <p>Қазақ тілінде IT-курстарды үйреніп, болашағыңызды жасаңыз!</p>
      <a href="courses.php" class="btn" href="#">Бастау</a>
    </div>
  </div>
</section>

<div class="categories">
  <div class="heading">
    <span>Санаттар</span>
    <h1>Жеке дамуыңа әсер ететін<br>үздік курстар</h1>
  </div>
  <div class="box-container">
    <div class="box">
      <img src="image/server.png">
      <h3>IT және бағдарламалық жасақтама</h3>
      <a href="courses.php">13 courses <i class="bx bx-right-arrow-alt"></i></a>
    </div>
    <div class="box">
      <img src="image/web-design.png">
      <h3>Веб-дизайн</h3>
      <a href="courses.php">15 courses <i class="bx bx-right-arrow-alt"></i></a>
    </div>
    <div class="box">
      <img src="image/smartphone.png">
      <h3>Мобильді қосымшалар</h3>
      <a href="courses.php">14 courses <i class="bx bx-right-arrow-alt"></i></a>
    </div>
    <div class="box">
      <img src="image/design.png">
      <h3>Графикалық дизайн</h3>
      <a href="courses.php">9 courses <i class="bx bx-right-arrow-alt"></i></a>
    </div>
    <div class="box">
      <img src="image/server.png">
      <h3>Компьютерлік математика</h3>
      <a href="courses.php">6 courses <i class="bx bx-right-arrow-alt"></i></a>
    </div>
    <div class="box">
      <img src="image/personal.png">
      <h3>Бағдарламалау тілдері</h3>
      <a href="courses.php">13 courses <i class="bx bx-right-arrow-alt"></i></a>
    </div>
  </div>
</div>

<!--- benefits -- -->
<div class="benifites">
  <img src="image/map.png">
  <div class="detail">
    <h1>Мыңдаған тұтынушылар сеніміне ие</h1>
    <p>Өзіне сең. Тырыс. Ақылды жұмыс істе</p>
    <a href="courses.php" class="btn">Курстарды қазір зерттеңіз</a>
    <p>OQYLAB МАҒАН ҚАНДАЙ ПАЙДА ӘКЕЛЕДІ?</p>
    <div class="box-container">
      <div class="box">
        <img src="image/benefit-01.png">
        <p>Өмірбойы тегін жаңарту</p>
      </div>
      <div class="box">
        <img src="image/benefit-02.png">
        <p>24/7 уақыт бойы көмек<br></p>
      </div>
      <div class="box">
        <img src="image/benefit-03.png">
        <p>Жоғары жылдамдықты өнімділік</p>
      </div>
      <div class="box">
        <img src="image/benefit-04.png">
        <p>Біз премиум курстар ұсынамыз</p>
      </div>
      <div class="box">
        <img src="image/benefit-05.png">
        <p>Әзірлеушілерге ыңғайлы код және дизайн</p>
      </div>
    </div>
  </div>
</div>

<!------ courses selection ---- -->
<div class="courses">
  <div class="heading">
    <span>OqyLab бағдарламаның</span>
    <h1>Ең танымал курстары</h1>
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
      <a href="playlist.php?get_id=<?= $course_id;?>" class="btn">Толығырақ қарау</a>
    </div>
    <?php
    }
  }else{
    echo '<p class="empty">no courses added yet</p>';
  }
  ?>
  </div>
</div>

<div class="feedback-section">
    <h1>Білім – болашақтың кілті!</h1>
    <p>OqyLab арқылы жаңа мүмкіндіктерді ашып,<br> армандарыңа жақында!</p>
</div>

<style>
    
</style>


<div class="teacher-section">
  <div class="heading">
    <span>Біздің білікті ұстаздарымыз</span>
    <h1>Тәжірибелі мамандардан білім ал!</h1>
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
      <h2>Айша Құрманбаева</h2>
      <span>Frontend әзірлеуші, оқытушы</span>
      <p><i class="bx bx-location-plus"></i>Ақтау, Қазақстан</p>
      <p>Айша – 5 жылдық тәжірибесі бар frontend әзірлеуші. React, Vue.js және UI/UX дизайн бойынша маман. Ол студенттерге таза әрі заманауи код жазуды үйретеді.</p>
      <div class="icons">
        <i class="bx bxl-instagram"></i>
        <i class="bx bxl-twitter"></i>
        <i class="bx bxl-facebook"></i>
        <i class="bx bxl-linkedin"></i>
      </div>
      <a href=""><i class="bx bx-phone"></i>+7 777 123 4567</a>
      <a href=""><i class="bx bx-envelope"></i>aisha.kurm@oqylab.kz</a>
    </div>
  </div>
  <div class="tab-content" id="team-02">
    <img src="image/team-02.jpg">
    <div class="detail">
      <h2>Мадина Төлегенова</h2>
      <span>UX/UI дизайнер, оқытушы</span>
      <p><i class="bx bx-location-plus"></i>Алматы, Қазақстан</p>
      <p>Мадина 6 жылдан бері дизайн индустриясында. Ол Figma, Adobe XD және дизайн психологиясын үйретеді. Оқыту тәсілі – интерактивті және практикаға негізделген.</p>
      <div class="icons">
        <i class="bx bxl-instagram"></i>
        <i class="bx bxl-twitter"></i>
        <i class="bx bxl-facebook"></i>
        <i class="bx bxl-linkedin"></i>
      </div>
      <a href=""><i class="bx bx-phone"></i>+7 705 234 5678</a>
      <a href=""><i class="bx bx-envelope"></i>madina.tolegen@oqylab.kz</a>
    </div>
  </div>
  <div class="tab-content" id="team-03">
    <img src="image/team-03.jpg">
    <div class="detail">
      <h2>Дана Әлімханова</h2>
      <span>Fullstack әзірлеуші, оқытушы</span>
      <p><i class="bx bx-location-plus"></i>Астана, Қазақстан</p>
      <p>Дана – fullstack әзірлеуші, MERN және Laravel бойынша тәжірибесі бар. Оның сабақтарында студенттер нағыз жобалар жасап, портфолиосын толықтыра алады.</p>
      <div class="icons">
        <i class="bx bxl-instagram"></i>
        <i class="bx bxl-twitter"></i>
        <i class="bx bxl-facebook"></i>
        <i class="bx bxl-linkedin"></i>
      </div>
      <a href=""><i class="bx bx-phone"></i>+7 776 456 7890</a>
      <a href=""><i class="bx bx-envelope"></i>dana.alim@oqylab.kz</a>
    </div>
  </div>
  <div class="tab-content" id="team-04">
    <img src="image/team-04.jpg">
    <div class="detail">
      <h2>Аружан Бекмұратова</h2>
      <span> Мобильді әзірлеуші, оқытушы</span>
      <p><i class="bx bx-location-plus"></i>Астана, Қазақстан</p>
      <p>Аружан Flutter және Swift бойынша 4 жылдық тәжірибесі бар мобильді әзірлеуші. Ол студенттерге iOS және Android қосымшалар жасауды үйретеді.</p>
      <div class="icons">
        <i class="bx bxl-instagram"></i>
        <i class="bx bxl-twitter"></i>
        <i class="bx bxl-facebook"></i>
        <i class="bx bxl-linkedin"></i>
      </div>
      <a href=""><i class="bx bx-phone"></i>+7 708 678 9012</a>
      <a href=""><i class="bx bx-envelope"></i>aruzhan.bekmurat@oqylab.kz</a>
    </div>
  </div>
  <div class="tab-content" id="team-05">
    <img src="image/team-05.jpg">
    <div class="detail">
      <h2>Темірлан Серікбаев</h2>
      <span>DevOps инженер, оқытушы</span>
      <p><i class="bx bx-location-plus"></i>Талдықорған, Қазақстан</p>
      <p>Темірлан DevOps пен бұлтты технологиялар саласының маманы. Docker, Kubernetes, CI/CD процестері және серверлік басқаруды үйретеді.</p>
      <div class="icons">
        <i class="bx bxl-instagram"></i>
        <i class="bx bxl-twitter"></i>
        <i class="bx bxl-facebook"></i>
        <i class="bx bxl-linkedin"></i>
      </div>
      <a href=""><i class="bx bx-phone"></i>+7 747 567 8901</a>
      <a href=""><i class="bx bx-envelope"></i>temirlan.serik@oqylab.kz</a>
    </div>
  </div>
  <div class="tab-content" id="team-06">
    <img src="image/team-06.jpg">
    <div class="detail">
      <h2>Нұрсұлтан Елубеков</h2>
      <span>Backend әзірлеуші, оқытушы</span>
      <p><i class="bx bx-location-plus"></i>Қостанай, Қазақстан</p>
      <p>Нұрсұлтан – Go және Python тілдері бойынша сарапшы. Ол backend архитектура, REST API және мәліметтер қорымен жұмыс істеуді үйретеді.</p>
      <div class="icons">
        <i class="bx bxl-instagram"></i>
        <i class="bx bxl-twitter"></i>
        <i class="bx bxl-facebook"></i>
        <i class="bx bxl-linkedin"></i>
      </div>
      <a href=""><i class="bx bx-phone"></i>+7 701 345 6789</a>
      <a href=""><i class="bx bx-envelope"></i>nursultan.elubek@oqylab.kz</a>
    </div>
  </div>
</div>

<div class="container" id="ads">
    <h1>IT саласындағы соңғы жаңалықтар</h1>
    <p>Соңғы IT жаңалықтарынан, хакатондардан және сарапшылардың кеңестерінен хабардар болып, оқу процесіңізді ұмытылмас етіңіз.</p>
    <div class="carousel">
        <button class="carousel-control prev" onclick="moveCarousel(-1)">&#10094;</button>
        <div class="carousel-inner">
            <a href="#" class="article-card">
                <div class="adv" style="background-image: url(image/news1.jpg);"></div>
                <div class="article-info">
                    <h4>Astana IT University Қорғаныс министрлігіне озық технологиялар мен цифрлық шешімдерді таныстырды</h4>
                    <div class="author-info">
                        <img src="image/client-03.png" alt="Author">
                        <div>
                            <strong>Қызболсын Білмеймінбаев</strong><br>
                            <i>Ақпан 14, 2025</i>
                        </div>
                    </div>
                </div>
            </a>
            <a href="#" class="article-card">
                <div class="adv" style="background-image: url(image/news3.jpg);"></div>
                <div class="article-info">
                    <h4>Қазақстанның IT-индустриясы: жаңа стартаптар мен инновациялар</h4>
                    <div class="author-info">
                        <img src="image/client-02.png" alt="Author">
                        <div>
                            <strong>Күлкігүл Кімекенова</strong><br>
                            <i>Ақпан 14, 2025</i>
                        </div>
                    </div>
                </div>
            </a>
            <a href="#" class="article-card">
                <div class="adv" style="background-image: url(image/new4.jpg);"></div>
                <div class="article-info">
                    <h4>Digital Almaty 2025: 40 мыңнан астам қатысушы цифрлық болашақты талқылады</h4>
                    <div class="author-info">
                        <img src="image/client-01.png" alt="Author">
                        <div>
                            <strong>Біреу Біреубаев</strong><br>
                            <i>Ақпан 14, 2025</i>
                        </div>
                    </div>
                </div>
            </a>
            <a href="#" class="article-card">
                <div class="adv" style="background-image: url(image/news5.jpg);"></div>
                <div class="article-info">
                    <h4>Қазақстан интернет жылдамдығы бойынша нешінші орында</h4>
                    <div class="author-info">
                        <img src="image/client-04.png" alt="Author">
                        <div>
                            <strong>Ойбайгүл Естігенова</strong><br>
                            <i>Ақпан 14, 2025</i>
                        </div>
                    </div>
                </div>
            </a>
            <a href="#" class="article-card">
                <div class="adv" style="background-image: url(image/new2.jpg);"></div>
                <div class="article-info">
                    <h4>Astana Hub жаңа IT жобаларды қолдауға гранттар бөлді</h4>
                    <div class="author-info">
                        <img src="image/client-08.png" alt="Author">
                        <div>
                            <strong>Сабырсыз Сабырханова</strong><br>
                            <i>Ақпан 14, 2025</i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <button class="carousel-control next" onclick="moveCarousel(1)">&#10095;</button>
    </div>
</div>

<div class="faq-sector" id="faq">
    <h1>Жиі қойылатын сұрақтар</h1>
    <div class="faq-item">
        <h4>OqyLab платформасына қалай тіркелуге болады?</h4>
        <p>Платформаға тіркелу үшін басты беттегі "Тіркелу" батырмасын басып, қажетті ақпаратты енгізіңіз.</p>
    </div>
    <div class="faq-item">
        <h4>Курстар кімдерге арналған?</h4>
        <p>OqyLab IT саласына қызығатын кез келген адамға, жаңа бастаушыларға және біліктілігін арттырғысы келетін мамандарға арналған.</p>
    </div>
    <div class="faq-item">
        <h4>Курстардың бағасы қандай?</h4>
        <p>Кейбір курстар тегін, ал кейбіреулері ақылы. Толық ақпаратты <a href="#">Курстар</a> бөлімінен біле аласыз.</p>
    </div>
    <div class="faq-item">
        <h4>Сертификат беріле ме?</h4>
        <p>Иә, курсты толық аяқтағаннан кейін сізге арнайы сертификат беріледі.</p>
    </div>
    <div class="faq-item">
        <h4>Платформада қандай бағыттар бар?</h4>
        <p>Бізде веб-бағдарламалау, мобильді қосымшалар, графикалық дизайн, компьютерлік математика және басқа да IT бағыттар бойынша курстар бар.</p>
    </div>
</div>



<style>

</style>

<script>
    function moveCarousel(direction) {
        const carousel = document.querySelector('.carousel-inner');
        carousel.scrollBy({ left: direction * 320, behavior: 'smooth' });
    }
</script>


<?php include __DIR__ . '/components/footer.php'; ?>
<script src="/../js/user_script.js"></script>
</body>
</html>
