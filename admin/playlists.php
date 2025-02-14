<?php
include __DIR__ . '/../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    $tutor_id = '';
}

if (isset($_POST['delete'])) {
    $delete_id = filter_var($_POST['playlist_id'], FILTER_SANITIZE_SPECIAL_CHARS);

    $verify_playlist = $conn->prepare('SELECT * FROM `playlist` WHERE id=? AND tutor_id=? LIMIT 1');
    $verify_playlist->execute([$delete_id, $tutor_id]);

    if ($verify_playlist->rowCount() > 0) {
        $fetch_thumb = $verify_playlist->fetch(PDO::FETCH_ASSOC);
        $thumb_path = '../uploaded_files/' . $fetch_thumb['thumb'];

        if (file_exists($thumb_path)) {
            unlink($thumb_path);
        }

        // Удаляем записи из связанных таблиц
        $delete_bookmark = $conn->prepare('DELETE FROM `bookmark` WHERE playlist_id=?');
        $delete_bookmark->execute([$delete_id]);

        $delete_playlist = $conn->prepare('DELETE FROM `playlist` WHERE id=?');
        $delete_playlist->execute([$delete_id]);

        $message[] = 'Playlist deleted successfully';
    } else {
        $message[] = 'Playlist does not exist or was already deleted';
    }
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
    <?php include __DIR__ . '/../components/admin_header.php'; ?>

    <section class="playlists">
        <h1 class="heading">Added Playlists</h1>

        <div class="box-container">
            <div class="add">
                <a href="add_playlist.php"><i class="bx bx-plus"></i></a>
            </div>

            <?php
            $select_playlist = $conn->prepare('SELECT * FROM `playlist` WHERE tutor_id=? ORDER BY date DESC');
            $select_playlist->execute([$tutor_id]);

            if ($select_playlist->rowCount() > 0) {
                while ($row = $select_playlist->fetch(PDO::FETCH_ASSOC)) {
                    $playlist_id = $row['id'];
                    
                    $count_videos = $conn->prepare('SELECT * FROM `content` WHERE playlist_id=?');
                    $count_videos->execute([$playlist_id]);
                    $total_videos = $count_videos->rowCount();
            ?>
                    <div class="box">
                        <div class="flex">
                            <div>
                                <i class="bx bx-dots-vertical-rounded" style="<?= ($row['status'] == 'active') ? 'color:limegreen' : 'color:red'; ?>"></i>
                                <span style="<?= ($row['status'] == 'active') ? 'color:limegreen' : 'color:red'; ?>">
                                    <?= htmlspecialchars($row['status']); ?>
                                </span>
                            </div>
                        </div>
                        <div><i class="bx bx-calendar"></i><span><?= htmlspecialchars($row['date']); ?></span>
                    </div>
                        <div class="thumb">
                            <span><?= $total_videos; ?></span>
                            <img src="../uploaded_files/<?= htmlspecialchars($row['thumb']); ?>" alt="Playlist Thumbnail">
                        </div>
                        <h3 class="title"><?= htmlspecialchars($row['title']); ?></h3>
                        <p class="description"><?= htmlspecialchars($row['description']); ?></p>
                        <form action="" method="post" class="flex-btn">
                            <input type="hidden" name="playlist_id" value="<?= $playlist_id; ?>">
                            <a href="update_playlist.php?get_id=<?= $playlist_id; ?>" class="btn">Update</a>
                            <input type="submit" name="delete" value="Delete" class="btn" onclick="return confirm('Delete this playlist?');">
                            <a href="view_playlist.php?get_id=<?= $playlist_id; ?>" class="btn">View Playlist</a>
                        </form>
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">No playlist added yet!</p>';
            }
            ?>
        </div>
    </section>  

    <?php include __DIR__ . '/../components/footer.php'; ?>
    <script src="/../js/admin_script.js"></script>
</body>
</html>
