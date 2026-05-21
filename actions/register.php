<?php
require_once '../config/auth.php';
require_once '../config/db.php';

// Если страницу открыли не через форму, возвращаем на главную
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('../index.php');
}

$email = trim($_POST['email'] ?? '');
$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$password = $_POST['password'] ?? '';
$password_repeat = $_POST['password_repeat'] ?? '';

$name = trim($first_name . ' ' . $last_name);

// Проверка на пустые поля
if ($email == '' || $name == '' || $password == '' || $password_repeat == '') {
    redirect('../index.php?error=empty_register');
}

// Проверка email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect('../index.php?error=wrong_email');
}

// Проверка паролей
if ($password != $password_repeat) {
    redirect('../index.php?error=passwords_not_equal');
}

// Проверяем, есть ли уже такой email
$email_safe = mysqli_real_escape_string($connect, $email);
$sql = "SELECT id FROM users WHERE email = '$email_safe'";
$result = mysqli_query($connect, $sql);

if (mysqli_num_rows($result) > 0) {
    redirect('../index.php?error=email_exists');
}

// Хешируем пароль, чтобы не хранить его обычным текстом
$password_hash = password_hash($password, PASSWORD_DEFAULT);
$password_safe = mysqli_real_escape_string($connect, $password_hash);
$name_safe = mysqli_real_escape_string($connect, $name);
$role = 'musician';

// Добавляем пользователя в базу данных
$sql = "INSERT INTO users (email, password, name, role)
        VALUES ('$email_safe', '$password_safe', '$name_safe', '$role')";

if (!mysqli_query($connect, $sql)) {
    die('Ошибка регистрации');
}

// Сразу авторизуем пользователя после регистрации
$user_id = mysqli_insert_id($connect);

$_SESSION['user'] = [
    'id' => $user_id,
    'email' => $email,
    'name' => $name,
    'role' => $role
];

redirect('../profile.php');
?>
