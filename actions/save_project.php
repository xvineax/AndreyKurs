<?php
require_once '../config/auth.php';
require_once '../config/db.php';

if (!is_auth()) {
    redirect('../index.php');
}

if (!is_admin()) {
    redirect('../profile.php?error=not_admin');
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('../admin.php?tab=projects');
}

// Простая функция для загрузки картинки проекта
function upload_project_image($old_image) {
    if (!isset($_FILES['image']) || $_FILES['image']['error'] != 0) {
        return $old_image;
    }

    $file_name = $_FILES['image']['name'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $allowed_ext = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    if (!in_array($file_ext, $allowed_ext)) {
        return $old_image;
    }

    $folder = '../assets/uploads/projects/';

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $new_file_name = time() . '_' . rand(1000, 9999) . '.' . $file_ext;
    $full_path = $folder . $new_file_name;

    if (move_uploaded_file($file_tmp, $full_path)) {
        return 'assets/uploads/projects/' . $new_file_name;
    }

    return $old_image;
}

$id = (int)($_POST['id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$artist = trim($_POST['artist'] ?? '');
$genre = trim($_POST['genre'] ?? '');
$description = trim($_POST['description'] ?? '');
$case_link = trim($_POST['case_link'] ?? '');
$user_id = (int)($_POST['user_id'] ?? 0);
$old_image = trim($_POST['old_image'] ?? '');
$image_url = upload_project_image($old_image);
$submission_id = (int)($_POST['submission_id'] ?? 0);

if ($title == '') {
    redirect('../admin.php?tab=projects&error=empty_project');
}

$title = mysqli_real_escape_string($connect, $title);
$artist = mysqli_real_escape_string($connect, $artist);
$genre = mysqli_real_escape_string($connect, $genre);
$description = mysqli_real_escape_string($connect, $description);
$case_link = mysqli_real_escape_string($connect, $case_link);
$image_url = mysqli_real_escape_string($connect, $image_url);
$user_id_sql = $user_id > 0 ? $user_id : 'NULL';

if ($id > 0) {
    $sql = "UPDATE projects SET
            title = '$title',
            artist = '$artist',
            genre = '$genre',
            description = '$description',
            case_link = '$case_link',
            image_url = '$image_url',
            user_id = $user_id_sql
            WHERE id = $id";

    mysqli_query($connect, $sql);
    redirect('../admin.php?tab=projects&success=project_updated');
}

$sql = "INSERT INTO projects (title, artist, genre, description, case_link, image_url, user_id)
        VALUES ('$title', '$artist', '$genre', '$description', '$case_link', '$image_url', $user_id_sql)";

mysqli_query($connect, $sql);

// Если проект создан по заявке, помечаем заявку как одобренную
if ($submission_id > 0) {
    mysqli_query($connect, "UPDATE submissions_musician SET status = 'approved' WHERE id = $submission_id");
}

redirect('../admin.php?tab=projects&success=project_added');
?>
