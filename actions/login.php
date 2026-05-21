<?php
require_once '../config/auth.php';
require_once '../config/db.php';

// Если страницу открыли не через форму, возвращаем на главную
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('../index.php');
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Проверка на пустые поля
if ($email == '' || $password == '') {
    redirect('../index.php?error=empty_login');
}

$email_safe = mysqli_real_escape_string($connect, $email);

// Ищем пользователя по email
$sql = "SELECT * FROM users WHERE email = '$email_safe'";
$result = mysqli_query($connect, $sql);
$user = mysqli_fetch_assoc($result);

// Проверяем пользователя и пароль
if (!$user || !password_verify($password, $user['password'])) {
    redirect('../index.php?error=wrong_login');
}

// Сохраняем данные пользователя в сессию
$_SESSION['user'] = [
    'id' => $user['id'],
    'email' => $user['email'],
    'name' => $user['name'],
    'role' => $user['role']
];

redirect('../profile.php');
?>
