<?php
require_once '../config/auth.php';
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('../for-whom.php');
}

// Заявку может отправить только пользователь, который вошёл в аккаунт
if (!is_auth()) {
    redirect('../for-whom.php?error=need_auth');
}

$user = current_user();
$user_id = (int)$user['id'];
$name = mysqli_real_escape_string($connect, $user['name']);
$email = mysqli_real_escape_string($connect, $user['email']);
$genre = trim($_POST['genre'] ?? '');
$project_description = trim($_POST['project_description'] ?? '');

if ($project_description == '') {
    redirect('../for-whom.php?error=empty_submission');
}

$genre = mysqli_real_escape_string($connect, $genre);
$project_description = mysqli_real_escape_string($connect, $project_description);

$sql = "INSERT INTO submissions_musician (name, email, genre, project_description, user_id)
        VALUES ('$name', '$email', '$genre', '$project_description', $user_id)";

$result = mysqli_query($connect, $sql);

if (!$result) {
    die('Ошибка при отправке заявки');
}

$submission_id = mysqli_insert_id($connect);

// Загружаем несколько файлов, если они были выбраны
if (!empty($_FILES['files']['name'][0])) {
    $upload_dir = '../assets/uploads/submissions/';

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'pdf', 'doc', 'docx', 'txt', 'zip', 'rar'];

    for ($i = 0; $i < count($_FILES['files']['name']); $i++) {
        if ($_FILES['files']['error'][$i] != 0) {
            continue;
        }

        $original_name = $_FILES['files']['name'][$i];
        $tmp_name = $_FILES['files']['tmp_name'][$i];
        $extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

        if (!in_array($extension, $allowed_extensions)) {
            continue;
        }

        $new_name = 'submission_' . $submission_id . '_' . time() . '_' . $i . '.' . $extension;
        $file_path = $upload_dir . $new_name;
        $db_path = 'assets/uploads/submissions/' . $new_name;

        if (move_uploaded_file($tmp_name, $file_path)) {
            $safe_original_name = mysqli_real_escape_string($connect, $original_name);
            $safe_db_path = mysqli_real_escape_string($connect, $db_path);

            mysqli_query($connect, "INSERT INTO submission_files (submission_id, file_name, file_path)
                                    VALUES ($submission_id, '$safe_original_name', '$safe_db_path')");
        }
    }
}

redirect('../for-whom.php?success=submission_sent');
?>
