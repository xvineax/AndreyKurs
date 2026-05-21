<?php require_once __DIR__ . '/config/auth.php'; ?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Процесс — Soundframe Design</title>
  <link rel="stylesheet" href="assets/styles/main.css" />
  <link rel="stylesheet" href="assets/styles/process.css" />
</head>
<body>
  <?php if (isset($_GET['error'])): ?>
    <div class="site-message site-message-error">
      <?php
        $messages = [
          'empty_login' => 'Заполните email и пароль.',
          'wrong_login' => 'Неверный email или пароль.',
          'empty_register' => 'Заполните все поля регистрации.',
          'wrong_email' => 'Введите корректный email.',
          'passwords_not_equal' => 'Пароли не совпадают.',
          'email_exists' => 'Пользователь с таким email уже существует.',
          'empty_profile' => 'Заполните имя, фамилию и email.',
          'not_admin' => 'У вас нет доступа к админ-панели.',
        ];
        echo e($messages[$_GET['error']] ?? 'Произошла ошибка.');
      ?>
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['success'])): ?>
    <div class="site-message site-message-success">Данные успешно сохранены.</div>
  <?php endif; ?>

  <header class="site-header">
    <div class="container header-inner">
      <a class="logo" href="index.php" aria-label="Soundframe Design">
        <span class="logo-soundframe">SOUNDFRAME</span>
        <span class="logo-design">DESIGN</span>
      </a>

      <nav class="nav" aria-label="Основная навигация">
        <a href="portfolio.php">Портфолио</a>
        <a class="is-active" href="process.php">Процесс</a>
        <a href="for-whom.php">Для кого</a>
        <?php if (is_admin()): ?>
          <a href="admin.php">Админ-панель</a>
        <?php endif; ?>
        <a href="#">Комьюнити</a>
        <a class="open-contacts" href="#">Контакты</a>
      </nav>

      <?php if (is_auth()): ?>
        <a class="header-login" href="profile.php">ПРОФИЛЬ</a>
      <?php else: ?>
        <a class="header-login open-auth" href="#">ВОЙТИ</a>
      <?php endif; ?>
    </div>
  </header>

  <main class="process-page">
    <section class="process-section">
      <div class="container process-inner">
        <h1 class="process-title">Как это работает</h1>
        <p class="process-subtitle">
          Прозрачный и защищенный процесс от заявки до релиза. Каждый этап<br>
          сопровождается юридическим договором и защитой прав.
        </p>

        <div class="process-grid">
          <article class="process-card">
            <div class="process-card-top">
              <span class="process-card-icon" aria-hidden="true">
                <img src="assets/icons/telegram.png" alt="">
              </span>
              <span class="process-card-number">01</span>
            </div>
            <h2 class="process-card-title">Подача заявки</h2>
            <p class="process-card-text">
              Заполните форму с описанием проекта, жанром и референсами. Наш куратор
              свяжется с вами в течение 24 часов.
            </p>
          </article>

          <article class="process-card">
            <div class="process-card-top">
              <span class="process-card-icon" aria-hidden="true">
                <img src="assets/icons/contract.png" alt="">
              </span>
              <span class="process-card-number">02</span>
            </div>
            <h2 class="process-card-title">Юридический<br>договор</h2>
            <p class="process-card-text">
              Подписываем договор, который защищает права на музыку и дизайн. Оплата
              делится на этапы.
            </p>
          </article>

          <article class="process-card">
            <div class="process-card-top">
              <span class="process-card-icon" aria-hidden="true">
                <img src="assets/icons/multiple-users-silhouette.png" alt="">
              </span>
              <span class="process-card-number">03</span>
            </div>
            <h2 class="process-card-title">Подбор дизайнера</h2>
            <p class="process-card-text">
              Мы подбираем дизайнера из нашего комьюнити, чей стиль максимально соответствует
              вашему проекту.
            </p>
          </article>

          <article class="process-card">
            <div class="process-card-top">
              <span class="process-card-icon" aria-hidden="true">
                <img src="assets/icons/rocket.png" alt="">
              </span>
              <span class="process-card-number">04</span>
            </div>
            <h2 class="process-card-title">Релиз и<br>продвижение</h2>
            <p class="process-card-text">
              Готовый проект попадает в наше портфолио и получает продвижение в соцсетях
              платформы.
            </p>
          </article>
        </div>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="container footer-inner">
      <div class="footer-about">
        <a class="footer-logo" href="index.php" aria-label="Soundframe Design">
          <span class="footer-logo-soundframe">Soundframe</span><span class="footer-logo-design">Design</span>
        </a>
        <p class="footer-text">
          Кураторская платформа для создания и продвижения уникального
          визуального оформления для нишевой музыкальной сцены.
        </p>
      </div>

      <div class="footer-column">
        <h2 class="footer-title">Навигация</h2>
        <nav class="footer-nav" aria-label="Навигация в подвале сайта">
          <a href="portfolio.php">Портфолио</a>
          <a href="process.php">Процесс работы</a>
          <a href="for-whom.php">Для кого</a>
        <?php if (is_admin()): ?>
          <a href="admin.php">Админ-панель</a>
        <?php endif; ?>
          <a href="#">Комьюнити</a>
          <a href="#">Блог</a>
        </nav>
      </div>

      <div class="footer-column">
        <h2 class="footer-title">Контакты</h2>
        <div class="footer-contacts">
          <a href="mailto:hello@soundframe.design">hello@soundframe.design</a>
          <a class="open-contacts" href="#">Telegram: @soundframedesign</a>
          <a href="#">+7 (123) 456-78-90</a>
          <p>Для лейблов:<br><a href="mailto:labels@soundframe.design">labels@soundframe.design</a></p>
          <p>Для дизайнеров:<br><a href="mailto:join@soundframe.design">join@soundframe.design</a></p>
        </div>
      </div>

      <div class="footer-column">
        <h2 class="footer-title">Жанры</h2>
        <ul class="footer-genres">
          <li>Drum &amp; Bass</li>
          <li>Techno</li>
          <li>Acid</li>
          <li>Пост-хардкор</li>
          <li>Экспериментальная электроника</li>
        </ul>
      </div>
    </div>

    <div class="container footer-bottom">
      <p>© 2026 Soundframe Design. Все права защищены. Только для некоммерческой музыки.</p>
    </div>
  </footer>

  <dialog class="auth-dialog" id="auth-modal">
    <div class="auth-modal">
      <div class="auth-modal-media">
        <div class="auth-modal-icon">♫</div>
        <button class="auth-modal-close close-auth" type="button" aria-label="Закрыть">×</button>
        <a class="auth-modal-logo" href="index.php" aria-label="Soundframe Design">
          <span class="auth-modal-logo-light">SOUNDFRAME</span>
          <span class="auth-modal-logo-green">DESIGN</span>
        </a>
      </div>

      <div class="auth-modal-body">
        <div class="auth-tabs" role="tablist" aria-label="Переключение формы входа и регистрации">
          <button class="auth-tab-button is-active auth-tab-login" type="button">Вход</button>
          <button class="auth-tab-button auth-tab-register" type="button">Регистрация</button>
        </div>

        <section class="auth-panel auth-form-login">
          <h2 class="auth-panel-title">Добро пожаловать</h2>
          <p class="auth-panel-subtitle">Войдите в свой аккаунт Soundframe Design</p>

          <form class="auth-form" action="actions/login.php" method="post">
            <label class="auth-form-field">
              <span>EMAIL</span>
              <input type="email" name="email" placeholder="sound@design.com" required />
            </label>

            <label class="auth-form-field">
              <span>ПАРОЛЬ</span>
              <input type="password" name="password" placeholder="••••••••" required />
            </label>

            <button class="auth-form-submit" type="submit">ВОЙТИ</button>
          </form>

          <p class="auth-panel-switch"><a class="switch-register" href="#">Зарегистрироваться</a><span>Нет аккаунта?</span></p>
        </section>

        <section class="auth-panel auth-form-register" hidden>
          <h2 class="auth-panel-title">Регистрация</h2>
          <p class="auth-panel-subtitle">Создайте аккаунт Soundframe Design</p>

          <form class="auth-form" action="actions/register.php" method="post">
            <label class="auth-form-field">
              <span>EMAIL</span>
              <input type="email" name="email" placeholder="sound@design.com" required />
            </label>

            <div class="auth-form-row">
              <label class="auth-form-field">
                <span>ИМЯ</span>
                <input type="text" name="first_name" placeholder="Иван" required />
              </label>

              <label class="auth-form-field">
                <span>ФАМИЛИЯ</span>
                <input type="text" name="last_name" placeholder="Иванов" required />
              </label>
            </div>

            <label class="auth-form-field">
              <span>ПАРОЛЬ</span>
              <input type="password" name="password" placeholder="••••••••" required />
            </label>

            <label class="auth-form-field">
              <span>ПОВТОР ПАРОЛЯ</span>
              <input type="password" name="password_repeat" placeholder="••••••••" required />
            </label>

            <label class="auth-form-check">
              <input type="checkbox" name="personal_data" required />
              <span>Я согласен на обработку персональных данных.</span>
            </label>

            <button class="auth-form-submit" type="submit">ЗАРЕГИСТРИРОВАТЬСЯ</button>
          </form>

          <p class="auth-panel-switch"><a class="switch-login" href="#">Войти</a><span>Есть аккаунт?</span></p>
        </section>
      </div>
    </div>
  </dialog>

  <dialog class="contacts-dialog" id="contacts-modal">
    <div class="contacts-modal">
      <div class="contacts-modal-header">
        <div>
          <p class="contacts-modal-label">Связь с платформой</p>
          <h2 class="contacts-modal-title">Контакты</h2>
        </div>

        <button class="contacts-modal-close close-contacts" type="button" aria-label="Закрыть">×</button>
      </div>

      <div class="contacts-modal-content">
        <section class="contacts-modal-section">
          <h3>Основная связь</h3>
          <a href="mailto:hello@soundframe.design">hello@soundframe.design</a>
          <a href="#">Telegram: @soundframedesign</a>
        </section>

        <section class="contacts-modal-section">
          <h3>Для лейблов</h3>
          <a href="mailto:labels@soundframe.design">labels@soundframe.design</a>
        </section>

        <section class="contacts-modal-section">
          <h3>Для дизайнеров</h3>
          <a href="mailto:join@soundframe.design">join@soundframe.design</a>
        </section>
      </div>

      <p class="contacts-modal-text">
        Напишите нам, если хотите запустить визуальное оформление релиза,
        обсудить сотрудничество или присоединиться к комьюнити дизайнеров.
      </p>
    </div>
  </dialog>

  <script src="assets/scripts/modal.js"></script>
</body>
</html>
