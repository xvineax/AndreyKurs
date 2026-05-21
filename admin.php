<?php
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/config/db.php';

if (!is_auth()) {
    redirect('index.php');
}

if (!is_admin()) {
    redirect('profile.php?error=not_admin');
}

// По умолчанию открываем вкладку с проектами
$tab = $_GET['tab'] ?? 'projects';

if ($tab != 'projects' && $tab != 'users' && $tab != 'submissions') {
    $tab = 'projects';
}

// Берём проекты вместе с именем пользователя, к которому они привязаны
$projects = mysqli_query($connect, "SELECT projects.*, users.name AS user_name
                                    FROM projects
                                    LEFT JOIN users ON projects.user_id = users.id
                                    ORDER BY projects.id DESC");

// Пользователи нужны для списка и для выбора владельца проекта
$users = mysqli_query($connect, "SELECT * FROM users ORDER BY id DESC");
$users_for_project = mysqli_query($connect, "SELECT * FROM users ORDER BY name ASC");

// Берём заявки, которые отправили пользователи со страницы "Для кого"
$submissions = mysqli_query($connect, "SELECT submissions_musician.*, users.name AS user_name, users.email AS user_email
                                       FROM submissions_musician
                                       LEFT JOIN users ON submissions_musician.user_id = users.id
                                       ORDER BY submissions_musician.id DESC");

// Если админ нажал "Создать проект" у заявки, заполняем форму проекта данными заявки
$selected_submission = null;
$submission_id = (int)($_GET['submission_id'] ?? 0);

if ($submission_id > 0) {
    $submission_result = mysqli_query($connect, "SELECT * FROM submissions_musician WHERE id = $submission_id");
    $selected_submission = mysqli_fetch_assoc($submission_result);
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Админ-панель — Soundframe Design</title>
  <link rel="stylesheet" href="assets/styles/main.css" />
  <link rel="stylesheet" href="assets/styles/admin.css" />
</head>
<body>
  <?php if (isset($_GET['success'])): ?>
    <div class="site-message site-message-success">
      <?php
        $success = [
          'project_added' => 'Проект добавлен.',
          'project_updated' => 'Проект обновлён.',
          'project_deleted' => 'Проект удалён.',
          'user_added' => 'Пользователь добавлен.',
          'user_updated' => 'Пользователь обновлён.',
          'user_deleted' => 'Пользователь удалён.',
          'submission_updated' => 'Статус заявки обновлён.'
        ];
        echo e($success[$_GET['success']] ?? 'Готово.');
      ?>
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['error'])): ?>
    <div class="site-message site-message-error">
      <?php
        $errors = [
          'empty_project' => 'Заполните название проекта.',
          'project_not_found' => 'Проект не найден.',
          'delete_error' => 'Не получилось удалить.',
          'empty_user' => 'Заполните имя, email и роль.',
          'empty_password' => 'Введите пароль для нового пользователя.',
          'email_exists' => 'Пользователь с таким email уже есть.',
          'user_not_found' => 'Пользователь не найден.',
          'cant_delete_self' => 'Нельзя удалить свой аккаунт.',
          'submission_not_found' => 'Заявка не найдена.'
        ];
        echo e($errors[$_GET['error']] ?? 'Произошла ошибка.');
      ?>
    </div>
  <?php endif; ?>

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
        <a class="is-active" href="admin.php">Админ-панель</a>
      </nav>

      <a class="header-login" href="profile.php">ПРОФИЛЬ</a>
    </div>
  </header>

  <main class="admin-page">
    <section class="container admin-inner">
      <div class="admin-title-block">
        <p class="admin-label">Управление сайтом</p>
        <h1 class="admin-title">Админ-панель</h1>
        <p class="admin-text">Здесь можно отдельно управлять проектами портфолио и пользователями сайта.</p>
      </div>

      <nav class="admin-tabs">
        <a class="admin-tab <?= $tab == 'projects' ? 'admin-tab-active' : '' ?>" href="admin.php?tab=projects">Проекты</a>
        <a class="admin-tab <?= $tab == 'submissions' ? 'admin-tab-active' : '' ?>" href="admin.php?tab=submissions">Заявки</a>
        <a class="admin-tab <?= $tab == 'users' ? 'admin-tab-active' : '' ?>" href="admin.php?tab=users">Пользователи</a>
      </nav>

      <?php if ($tab == 'projects'): ?>
        <section class="admin-card">
          <h2 class="admin-card-title">Добавить проект</h2>

          <?php if ($selected_submission): ?>
            <p class="admin-note">Форма заполнена по заявке #<?= e($selected_submission['id']) ?>. Проверьте данные, загрузите картинку и создайте проект.</p>
          <?php endif; ?>

          <form class="admin-form" action="actions/save_project.php" method="post" enctype="multipart/form-data">
            <?php if ($selected_submission): ?>
              <input type="hidden" name="submission_id" value="<?= e($selected_submission['id']) ?>">
            <?php endif; ?>
            <label>
              <span>Название проекта *</span>
              <input type="text" name="title" value="" placeholder="Aether Drift" required>
            </label>

            <label>
              <span>Автор / артист</span>
              <input type="text" name="artist" value="<?= e($selected_submission['name'] ?? '') ?>" placeholder="Liquid Forms">
            </label>

            <label>
              <span>Жанр</span>
              <input type="text" name="genre" value="<?= e($selected_submission['genre'] ?? '') ?>" placeholder="D&B">
            </label>

            <label>
              <span>Пользователь</span>
              <select name="user_id">
                <option value="0">Не привязывать</option>
                <?php if ($users_for_project): ?>
                  <?php while ($user = mysqli_fetch_assoc($users_for_project)): ?>
                    <option value="<?= e($user['id']) ?>" <?= isset($selected_submission['user_id']) && $selected_submission['user_id'] == $user['id'] ? 'selected' : '' ?>><?= e($user['name']) ?> — <?= e($user['email']) ?></option>
                  <?php endwhile; ?>
                <?php endif; ?>
              </select>
            </label>

            <label>
              <span>Картинка проекта</span>
              <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif">
            </label>

            <label>
              <span>Ссылка на проект</span>
              <input type="text" name="case_link" placeholder="#">
            </label>

            <label class="admin-form-full">
              <span>Описание</span>
              <textarea name="description" rows="4" placeholder="Краткое описание проекта"><?= e($selected_submission['project_description'] ?? '') ?></textarea>
            </label>

            <button class="admin-button" type="submit">Добавить проект</button>
          </form>
        </section>

        <section class="admin-card">
          <h2 class="admin-card-title">Проекты из базы данных</h2>

          <?php if ($projects && mysqli_num_rows($projects) > 0): ?>
            <div class="admin-table-wrap">
              <table class="admin-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Картинка</th>
                    <th>Название</th>
                    <th>Артист</th>
                    <th>Жанр</th>
                    <th>Пользователь</th>
                    <th>Действия</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($project = mysqli_fetch_assoc($projects)): ?>
                    <tr>
                      <td><?= e($project['id']) ?></td>
                      <td>
                        <?php if (!empty($project['image_url'])): ?>
                          <img class="admin-image" src="<?= e($project['image_url']) ?>" alt="">
                        <?php else: ?>
                          <span class="admin-muted">нет</span>
                        <?php endif; ?>
                      </td>
                      <td><?= e($project['title']) ?></td>
                      <td><?= e($project['artist']) ?></td>
                      <td><?= e($project['genre']) ?></td>
                      <td><?= e($project['user_name'] ?: 'Не выбран') ?></td>
                      <td class="admin-actions">
                        <a href="admin_edit_project.php?id=<?= e($project['id']) ?>">Редактировать</a>
                        <a class="admin-delete" href="actions/delete_project.php?id=<?= e($project['id']) ?>" onclick="return confirm('Удалить проект?')">Удалить</a>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <p class="admin-empty">В базе пока нет проектов.</p>
          <?php endif; ?>
        </section>
      <?php endif; ?>


      <?php if ($tab == 'submissions'): ?>
        <section class="admin-card">
          <h2 class="admin-card-title">Заявки с формы</h2>
          <p class="admin-text-small">Здесь появляются заявки, которые пользователи отправляют со страницы "Для кого".</p>

          <?php if ($submissions && mysqli_num_rows($submissions) > 0): ?>
            <div class="admin-table-wrap">
              <table class="admin-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Пользователь</th>
                    <th>Жанр</th>
                    <th>Описание</th>
                    <th>Файлы</th>
                    <th>Статус</th>
                    <th>Дата</th>
                    <th>Действия</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($submission = mysqli_fetch_assoc($submissions)): ?>
                    <tr>
                      <td><?= e($submission['id']) ?></td>
                      <td>
                        <strong><?= e($submission['user_name'] ?: $submission['name']) ?></strong><br>
                        <span class="admin-muted"><?= e($submission['user_email'] ?: $submission['email']) ?></span>
                      </td>
                      <td><?= e($submission['genre']) ?></td>
                      <td class="admin-description-cell"><?= e($submission['project_description']) ?></td>
                      <td>
                        <?php
                          $submission_id_for_files = (int)$submission['id'];
                          $files = mysqli_query($connect, "SELECT * FROM submission_files WHERE submission_id = $submission_id_for_files ORDER BY id ASC");
                        ?>

                        <?php if ($files && mysqli_num_rows($files) > 0): ?>
                          <div class="admin-files-list">
                            <?php while ($file = mysqli_fetch_assoc($files)): ?>
                              <a href="<?= e($file['file_path']) ?>" target="_blank"><?= e($file['file_name']) ?></a>
                            <?php endwhile; ?>
                          </div>
                        <?php else: ?>
                          <span class="admin-muted">нет</span>
                        <?php endif; ?>
                      </td>
                      <td><span class="admin-status admin-status-<?= e($submission['status']) ?>"><?= e($submission['status']) ?></span></td>
                      <td><?= e($submission['created_at']) ?></td>
                      <td class="admin-actions admin-actions-column">
                        <a href="actions/update_submission.php?id=<?= e($submission['id']) ?>&status=review">Взять в работу</a>
                        <a href="admin.php?tab=projects&submission_id=<?= e($submission['id']) ?>">Создать проект</a>
                        <a href="actions/update_submission.php?id=<?= e($submission['id']) ?>&status=approved">Одобрить</a>
                        <a class="admin-delete" href="actions/update_submission.php?id=<?= e($submission['id']) ?>&status=rejected">Отклонить</a>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <p class="admin-empty">Заявок пока нет.</p>
          <?php endif; ?>
        </section>
      <?php endif; ?>

      <?php if ($tab == 'users'): ?>
        <section class="admin-card">
          <h2 class="admin-card-title">Добавить пользователя</h2>

          <form class="admin-form" action="actions/save_user.php" method="post">
            <label>
              <span>Имя *</span>
              <input type="text" name="name" placeholder="Иван Иванов" required>
            </label>

            <label>
              <span>Email *</span>
              <input type="email" name="email" placeholder="user@mail.ru" required>
            </label>

            <label>
              <span>Пароль *</span>
              <input type="password" name="password" placeholder="123456" required>
            </label>

            <label>
              <span>Роль *</span>
              <select name="role" required>
                <option value="musician">Музыкант</option>
                <option value="designer">Дизайнер</option>
                <option value="admin">Админ</option>
              </select>
            </label>

            <button class="admin-button" type="submit">Добавить пользователя</button>
          </form>
        </section>

        <section class="admin-card">
          <h2 class="admin-card-title">Пользователи</h2>

          <?php if ($users && mysqli_num_rows($users) > 0): ?>
            <div class="admin-table-wrap">
              <table class="admin-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Роль</th>
                    <th>Дата регистрации</th>
                    <th>Действия</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($user = mysqli_fetch_assoc($users)): ?>
                    <tr>
                      <td><?= e($user['id']) ?></td>
                      <td><?= e($user['name']) ?></td>
                      <td><?= e($user['email']) ?></td>
                      <td><?= e($user['role']) ?></td>
                      <td><?= e($user['created_at']) ?></td>
                      <td class="admin-actions">
                        <a href="admin_edit_user.php?id=<?= e($user['id']) ?>">Редактировать</a>
                        <?php if ($user['id'] != current_user()['id']): ?>
                          <a class="admin-delete" href="actions/delete_user.php?id=<?= e($user['id']) ?>" onclick="return confirm('Удалить пользователя? Его проекты останутся без владельца.')">Удалить</a>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <p class="admin-empty">Пользователей пока нет.</p>
          <?php endif; ?>
        </section>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>
