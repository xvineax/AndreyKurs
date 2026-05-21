<?php
// Файл подключения к базе данных

$host = '95.215.56.125';
$db_user = 'Music';
$db_password = 'test82@@!test';
$db_name = 'Music';

$connect = mysqli_connect($host, $db_user, $db_password, $db_name);

if (!$connect) {
    die('Ошибка подключения к базе данных');
}
?>
