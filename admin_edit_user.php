<?php
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/config/db.php';

if (!is_auth()) {
    redirect('index.php');
}

if (!is_admin()) {
    redirect('profile.php?error=not_admin');
}

$id = (int)($_GET['id'] ?? 0);
$result = mysqli_query($connect, "SELECT * FROM users WHERE id = $id");
$user = mysqli_fetch_assoc($result);

if (!$user) {
    redirect('admin.php?tab=users&error=user_not_found');
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Редактирование пользователя — Soundframe Design</title>
  <link rel="stylesheet" href="assets/styles/main.css" />
  <link rel="stylesheet" href="assets/styles/admin.css" />
</head>
<body>
  <header class="site-header">
    <div class="container header-inner">
      <a class="logo" href="index.php" aria-label="Soundframe Design">
        <span class="logo-soundframe">SOUNDFRAME</span>
        <span class="logo-design">DESIGN</span>
      </a>

      <nav class="nav" aria-label="Основная навигация">
        <a href="portfolio.php">Портфолио</a>
        <a href="process.php">Процесс</a>
        <a href="for-whom.php">Для кого</a>
        <a class="is-active" href="admin.php?tab=users">Админ-панель</a>
      </nav>

      <a class="header-login" href="profile.php">ПРОФИЛЬ</a>
    </div>
  </header>

  <main class="admin-page">
    <section class="container admin-inner">
      <div class="admin-title-block">
        <p class="admin-label">Редактирование</p>
        <h1 class="admin-title">Изменить пользователя</h1>
        <p class="admin-text">Пароль можно оставить пустым, если его не нужно менять.</p>
      </div>

      <section class="admin-card">
        <form class="admin-form" action="actions/save_user.php" method="post">
          <input type="hidden" name="id" value="<?= e($user['id']) ?>">

          <label>
            <span>Имя *</span>
            <input type="text" name="name" value="<?= e($user['name']) ?>" required>
          </label>

          <label>
            <span>Email *</span>
            <input type="email" name="email" value="<?= e($user['email']) ?>" required>
          </label>

          <label>
            <span>Новый пароль</span>
            <input type="password" name="password" placeholder="Оставь пустым, если не меняешь">
          </label>

          <label>
            <span>Роль *</span>
            <select name="role" required>
              <option value="musician" <?= $user['role'] == 'musician' ? 'selected' : '' ?>>Музыкант</option>
              <option value="designer" <?= $user['role'] == 'designer' ? 'selected' : '' ?>>Дизайнер</option>
              <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Админ</option>
            </select>
          </label>

          <div class="admin-edit-buttons">
            <button class="admin-button" type="submit">Сохранить</button>
            <a class="admin-button admin-button-gray" href="admin.php?tab=users">Назад</a>
          </div>
        </form>
      </section>
    </section>
  </main>
</body>
</html>
