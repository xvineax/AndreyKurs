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
$status = $_GET['status'] ?? 'review';

$allowed = ['new', 'review', 'approved', 'rejected'];

if ($id <= 0 || !in_array($status, $allowed)) {
    redirect('../admin.php?tab=submissions&error=submission_not_found');
}

$status = mysqli_real_escape_string($connect, $status);
mysqli_query($connect, "UPDATE submissions_musician SET status = '$status' WHERE id = $id");

redirect('../admin.php?tab=submissions&success=submission_updated');
?>
