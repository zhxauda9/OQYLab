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
        <a href="home.php"><img src="image/logo.png" width="130px"></a>
        <nav class="navbar">
            <a href="home.php"><span>home</span></a>
            <a href="about.php"><span>about us</span></a>
            <a href="courses.php"><span>courses</span></a>
            <a href="teachers.php"><span>teachers</span></a>
            <a href="contact.php"><span>contact us</span></a>
        </nav>
        <form action="search_course.php" method="post" class="search-form">
            <input type="text" name="search_course" placeholder="search course.." required maxlength="100">
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
            <span>student</span><br>

            <div id="flex-btn">
                <a href="profile.php" class="btn">view profile</a>
                <a href="logout.php" onclick="return confirm('logout from this website?');" class="btn">logout</a>
            </div>
            <?php
                }else{
            ?>
            <h3>please login or register</h3>
            <div id="flex-btn">
                <a href="login.php" class="btn">login</a>
                <a href="register.php" class="btn">register</a>
            </div>
            <?php } ?>
        </div>
    </section>

</header>