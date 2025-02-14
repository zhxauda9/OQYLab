<!-- <?php
    if(isset($message)){
        foreach($message as $message){
            echo '
            <div class="message">
                <span>'.$message.'</span>
                <i class="bx bx-x" onclick="this/parentElement.remove();"></i>
            </div>
            ';
        }
    }
?> -->

<header class="header">
    <section class="flex">
        <a href="home.php"><img src="image/oqylab.png" width="130px"></a>
        <nav class="navbar">
            <a href="#"><span>Басты Бет</span></a>
            <a href="#"><span>Ақпарат</span></a>
            <a href="courses.php"><span>Сабақтар</span></a>
            <a href="#"><span>Ұстаздар</span></a>
            <a href="#"><span>Хабарласу</span></a>
        </nav>
        <form action="search_course.php" method="post" class="search-form">
            <input type="text" name="search_course" placeholder="пайтон сабақтары.." required maxlength="100">
            <button type="submit" name="search_course_btn" class="bx bx-search-alt-2"></button>
        </form>
        <div class="icons">
            <div id="menu-btn" class="bx bx-list-plus"></div>
            <div id="search-btn" class="bx bx-search-alt-2"></div>
            <div id="user-btn" class="bx bxs-user"></div>
        </div>
        <div class="profile">
            <?php
            $select_profile=$conn->prepare("SELECT * FROM `users` WHERE id=?");
            $select_profile->execute([$user_id]);
             if($select_profile->rowCount()> 0){
                $fetch_profile=$select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <img src="../uploaded_files/<?= $fetch_profile['image']; ?>">
            <h3><?= $fetch_profile['name'];?></h3>
            <span>Оқушы</span><br>

            <div id="flex-btn">
                <a href="profile.php" class="btn">Профиль қарау</a>
                <a href="logout.php" onclick="return confirm('шыққыңыз келеме?');" class="btn">Шығу</a>
            </div>
            <?php
                }else{
            ?>
            <h3>Профильге кіріңіз немесе тіркеліңіз</h3>
            <div id="flex-btn">
                <a href="login.php" class="btn">Кіру</a>
                <a href="register.php" class="btn">Тіркелу</a>
            </div>
            <?php } ?>
        </div>
    </section>

</header>