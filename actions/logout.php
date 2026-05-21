<?php
require_once '../config/auth.php';

// Очищаем сессию и выходим из аккаунта
session_destroy();

redirect('../index.php');
?>
