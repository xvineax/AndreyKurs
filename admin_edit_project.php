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
$result = mysqli_query($connect, "SELECT * FROM projects WHERE id = $id");
$project = mysqli_fetch_assoc($result);
$users = mysqli_query($connect, "SELECT * FROM users ORDER BY name ASC");

if (!$project) {
    redirect('admin.php?tab=projects&error=project_not_found');
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Редактирование проекта — Soundframe Design</title>
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
        <a class="is-active" href="admin.php?tab=projects">Админ-панель</a>
      </nav>

      <a class="header-login" href="profile.php">ПРОФИЛЬ</a>
    </div>
  </header>

  <main class="admin-page">
    <section class="container admin-inner">
      <div class="admin-title-block">
        <p class="admin-label">Редактирование</p>
        <h1 class="admin-title">Изменить проект</h1>
        <p class="admin-text">После сохранения данные обновятся в базе и на странице портфолио.</p>
      </div>

      <section class="admin-card">
        <form class="admin-form" action="actions/save_project.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?= e($project['id']) ?>">
          <input type="hidden" name="old_image" value="<?= e($project['image_url']) ?>">

          <label>
            <span>Название проекта *</span>
            <input type="text" name="title" value="<?= e($project['title']) ?>" required>
          </label>

          <label>
            <span>Автор / артист</span>
            <input type="text" name="artist" value="<?= e($project['artist']) ?>">
          </label>

          <label>
            <span>Жанр</span>
            <input type="text" name="genre" value="<?= e($project['genre']) ?>">
          </label>

          <label>
            <span>Пользователь</span>
            <select name="user_id">
              <option value="0">Не привязывать</option>
              <?php if ($users): ?>
                <?php while ($user = mysqli_fetch_assoc($users)): ?>
                  <option value="<?= e($user['id']) ?>" <?= $project['user_id'] == $user['id'] ? 'selected' : '' ?>>
                    <?= e($user['name']) ?> — <?= e($user['email']) ?>
                  </option>
                <?php endwhile; ?>
              <?php endif; ?>
            </select>
          </label>

          <label>
            <span>Новая картинка</span>
            <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif">
          </label>

          <label>
            <span>Ссылка на проект</span>
            <input type="text" name="case_link" value="<?= e($project['case_link']) ?>">
          </label>

          <?php if (!empty($project['image_url'])): ?>
            <div class="admin-form-full">
              <p class="admin-preview-title">Текущая картинка:</p>
              <img class="admin-preview-image" src="<?= e($project['image_url']) ?>" alt="">
            </div>
          <?php endif; ?>

          <label class="admin-form-full">
            <span>Описание</span>
            <textarea name="description" rows="4"><?= e($project['description']) ?></textarea>
          </label>

          <div class="admin-edit-buttons">
            <button class="admin-button" type="submit">Сохранить</button>
            <a class="admin-button admin-button-gray" href="admin.php?tab=projects">Назад</a>
          </div>
        </form>
      </section>
    </section>
  </main>
</body>
</html>
