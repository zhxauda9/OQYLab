<?php
include __DIR__ . '/../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    $tutor_id = '';
}

if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id'];
} else {
    header('location:playlist.php');
    exit();
}

$select_playlist = $conn->prepare('SELECT * FROM `playlist` WHERE id=? AND tutor_id=?');
$select_playlist->execute([$get_id, $tutor_id]);

if ($select_playlist->rowCount() > 0) {
    $fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC);
    $playlist_id = $fetch_playlist['id'];

    $count_videos = $conn->prepare('SELECT COUNT(*) FROM `content` WHERE playlist_id=?');
    $count_videos->execute([$playlist_id]);
    $total_videos = $count_videos->fetchColumn();
} else {
    echo '<p class="empty">No playlist found!</p>';
    exit();
}

if (isset($_POST['delete'])) {
    $delete_videos = $conn->prepare("DELETE FROM `content` WHERE playlist_id=?");
    $delete_videos->execute([$playlist_id]);
    $delete_playlist = $conn->prepare("DELETE FROM `playlist` WHERE id=?");
    $delete_playlist->execute([$playlist_id]);

    header('location:playlists.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Playlist Details</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
    <?php include __DIR__ . '/../components/admin_header.php'; ?>

    <div class="view-playlist">
        <h1 class="heading">Playlist Details</h1>

        <div class="row">
            <div class="thumb">
                <span><?= $total_videos; ?></span>
                <img src="../uploaded_files/<?= htmlspecialchars($fetch_playlist['thumb']); ?>" alt="Playlist Thumbnail">
            </div>
        </div>

        <div class="details">
            <h3 class="title"><?= htmlspecialchars($fetch_playlist['title']); ?></h3>
            <div class="date"><i class="bx bxs-calendar-alt"></i> <span><?= htmlspecialchars($fetch_playlist['date']); ?></span></div>
            <div class="description">
                <?= nl2br(htmlspecialchars($fetch_playlist['description'])); ?>
            </div>

            <form action="" method="post" class="flex-btn">
                <a href="update_playlist.php?get_id=<?= $playlist_id; ?>" class="btn">Edit Playlist</a>
                <input type="submit" name="delete" value="Delete Playlist" class="btn" onclick="return confirm('Are you sure you want to delete this playlist?');">
            </form>
        </div>
    </div>

    <?php include __DIR__ . '/../components/footer.php'; ?>
    <script src="/../js/admin_script.js"></script>
</body>
</html>
