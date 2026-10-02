<?php
// Обработка действий на странице «Пользователи»: вход, выход, добавление, удаление.
// Подключается из index.php ДО вывода HTML, чтобы работали redirect и сессия.
session_start();
require_once __DIR__ . '/users-config.inc.php';

spl_autoload_register(function ($class) {
    $file = __DIR__ . "/../classes/{$class}.php";
    if (is_file($file)) {
        require_once $file;
    }
});

// На сайте сообщения деструктора не нужны
User::$silent = true;

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}

function flash($type, $text) {
    $_SESSION['flash'] = array($type, $text);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $token = $_POST['csrf'] ?? '';
    $repo = new UserRepository(USERS_FILE);
    $isAdmin = !empty($_SESSION['is_admin']);

    if (!hash_equals($_SESSION['csrf'], $token)) {
        flash('err', 'Неверный токен формы. Обновите страницу и повторите.');
    } elseif ($action === 'login') {
        $admin = new SuperUser(ADMIN_NAME, ADMIN_LOGIN, ADMIN_PASSWORD, 'admin');
        if ($admin->auth(trim($_POST['login'] ?? ''), $_POST['password'] ?? '')) {
            session_regenerate_id(true);
            $_SESSION['is_admin'] = true;
            flash('ok', 'Вы вошли как администратор.');
        } else {
            flash('err', 'Неверный логин или пароль администратора.');
        }
    } elseif ($action === 'logout') {
        unset($_SESSION['is_admin']);
        flash('ok', 'Вы вышли из аккаунта администратора.');
    } elseif (!$isAdmin) {
        flash('err', 'Добавлять и удалять пользователей может только администратор.');
    } elseif ($action === 'add') {
        $error = $repo->add($_POST['name'] ?? '', $_POST['login'] ?? '', $_POST['password'] ?? '');
        if ($error === null) {
            flash('ok', 'Пользователь ' . trim($_POST['login']) . ' добавлен.');
        } else {
            flash('err', $error);
        }
    } elseif ($action === 'delete') {
        $login = $_POST['login'] ?? '';
        if ($repo->delete($login)) {
            flash('ok', 'Пользователь ' . $login . ' удалён.');
        } else {
            flash('err', 'Не удалось удалить: пользователь ' . $login . ' не найден.');
        }
    }

    // Post/Redirect/Get: после действия перезагружаем страницу, чтобы F5 не повторял его
    header('Location: index.php?id=users');
    exit;
}
