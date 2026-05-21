<?php
// Файл с простыми функциями для авторизации

session_start();

// Проверяем, вошёл ли пользователь в аккаунт
function is_auth() {
    return isset($_SESSION['user']);
}

// Получаем данные пользователя из сессии
function current_user() {
    if (isset($_SESSION['user'])) {
        return $_SESSION['user'];
    }

    return null;
}

// Простая переадресация на другую страницу
function redirect($page) {
    header('Location: ' . $page);
    exit;
}

// Защита вывода текста на страницу
function e($text) {
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

// Делим полное имя на имя и фамилию для формы редактирования
function split_full_name($name) {
    $parts = explode(' ', trim($name), 2);

    return [
        'first_name' => $parts[0] ?? '',
        'last_name' => $parts[1] ?? ''
    ];
}

// Получаем первые буквы имени для аватарки в профиле
function initials($user) {
    $name = trim($user['name'] ?? '');

    if ($name == '') {
        return '?';
    }

    $parts = explode(' ', $name);
    $first_letter = mb_substr($parts[0], 0, 1);
    $second_letter = '';

    if (isset($parts[1])) {
        $second_letter = mb_substr($parts[1], 0, 1);
    }

    return $first_letter . $second_letter;
}
?>
