<?php
require_once '../config/auth.php';
require_once '../config/db.php';

if (!is_auth()) {
    redirect('../index.php');
}

if (!is_admin()) {
    redirect('../profile.php?error=not_admin');
}

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    redirect('../admin.php?tab=users&error=user_not_found');
}

if ($id == current_user()['id']) {
    redirect('../admin.php?tab=users&error=cant_delete_self');
}

// Чтобы внешний ключ не мешал удалить пользователя,
// сначала отвязываем от него проекты и заявки
mysqli_query($connect, "UPDATE projects SET user_id = NULL WHERE user_id = $id");
mysqli_query($connect, "UPDATE submissions_musician SET user_id = NULL WHERE user_id = $id");

mysqli_query($connect, "DELETE FROM users WHERE id = $id");
redirect('../admin.php?tab=users&success=user_deleted');
?>
