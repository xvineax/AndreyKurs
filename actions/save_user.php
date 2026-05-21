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
    redirect('../admin.php?tab=users');
}

$id = (int)($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? 'musician';

$roles = ['musician', 'designer', 'admin'];

if ($name == '' || $email == '' || !in_array($role, $roles)) {
    redirect('../admin.php?tab=users&error=empty_user');
}

if ($id == 0 && $password == '') {
    redirect('../admin.php?tab=users&error=empty_password');
}

$name_safe = mysqli_real_escape_string($connect, $name);
$email_safe = mysqli_real_escape_string($connect, $email);
$role_safe = mysqli_real_escape_string($connect, $role);

// Проверяем, нет ли другого пользователя с таким email
$check = mysqli_query($connect, "SELECT id FROM users WHERE email = '$email_safe' AND id != $id");

if ($check && mysqli_num_rows($check) > 0) {
    redirect('../admin.php?tab=users&error=email_exists');
}

if ($id > 0) {
    $sql = "UPDATE users SET name = '$name_safe', email = '$email_safe', role = '$role_safe'";

    if ($password != '') {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $password_hash = mysqli_real_escape_string($connect, $password_hash);
        $sql .= ", password = '$password_hash'";
    }

    $sql .= " WHERE id = $id";
    mysqli_query($connect, $sql);

    // Если админ изменил сам себя, обновляем данные в сессии
    if ($id == current_user()['id']) {
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['role'] = $role;
    }

    redirect('../admin.php?tab=users&success=user_updated');
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);
$password_hash = mysqli_real_escape_string($connect, $password_hash);

$sql = "INSERT INTO users (name, email, password, role)
        VALUES ('$name_safe', '$email_safe', '$password_hash', '$role_safe')";

mysqli_query($connect, $sql);
redirect('../admin.php?tab=users&success=user_added');
?>
