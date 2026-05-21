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
    redirect('../admin.php?tab=projects&error=project_not_found');
}

$sql = "DELETE FROM projects WHERE id = $id";

if (mysqli_query($connect, $sql)) {
    redirect('../admin.php?tab=projects&success=project_deleted');
}

redirect('../admin.php?tab=projects&error=delete_error');
?>
