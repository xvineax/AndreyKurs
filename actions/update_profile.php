<?php
require_once '../config/auth.php';
require_once '../config/db.php';

// Если пользователь не вошёл, отправляем его на главную
if (!is_auth()) {
    redirect('../index.php');
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('../profile.php');
}

$user = current_user();
$user_id = $user['id'];

$email = trim($_POST['email'] ?? '');
$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$password = $_POST['password'] ?? '';
$password_repeat = $_POST['password_repeat'] ?? '';

$name = trim($first_name . ' ' . $last_name);

// Имя и email обязательны
if ($email == '' || $name == '') {
    redirect('../profile.php?error=empty_profile');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect('../profile.php?error=wrong_email');
}

$email_safe = mysqli_real_escape_string($connect, $email);
$name_safe = mysqli_real_escape_string($connect, $name);
$user_id_safe = (int)$user_id;

// Проверяем, не занят ли email другим пользователем
$sql = "SELECT id FROM users WHERE email = '$email_safe' AND id != $user_id_safe";
$result = mysqli_query($connect, $sql);

if (mysqli_num_rows($result) > 0) {
    redirect('../profile.php?error=email_exists');
}

// Если пользователь ввёл новый пароль, меняем и пароль тоже
if ($password != '') {
    if ($password != $password_repeat) {
        redirect('../profile.php?error=passwords_not_equal');
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $password_safe = mysqli_real_escape_string($connect, $password_hash);

    $sql = "UPDATE users
            SET name = '$name_safe', email = '$email_safe', password = '$password_safe'
            WHERE id = $user_id_safe";
} else {
    $sql = "UPDATE users
            SET name = '$name_safe', email = '$email_safe'
            WHERE id = $user_id_safe";
}

if (!mysqli_query($connect, $sql)) {
    die('Ошибка сохранения профиля');
}

// Обновляем данные в сессии
$_SESSION['user'] = [
    'id' => $user_id,
    'email' => $email,
    'name' => $name,
    'role' => $user['role']
];

redirect('../profile.php?success=profile_updated');
?>
