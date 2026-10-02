<?php
// Страница «Пользователи». Сессия, классы и обработка форм подключены в users-actions.inc.php
$repo = new UserRepository(USERS_FILE);
$users = $repo->all();
$isAdmin = !empty($_SESSION['is_admin']);
$csrf = $_SESSION['csrf'];
$e = function ($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); };

if (!empty($_SESSION['flash'])) {
    list($type, $text) = $_SESSION['flash'];
    unset($_SESSION['flash']);
    echo '<p class="msg msg-' . $e($type) . '">' . $e($text) . '</p>';
}

// Статус администратора
if ($isAdmin) {
    $admin = new SuperUser(ADMIN_NAME, ADMIN_LOGIN, ADMIN_PASSWORD, 'admin');
    $info = $admin->getInfo();
    ?>
    <form method="post" action="index.php?id=users" class="inline">
        Вы вошли как <b><?= $e($info['name']) ?></b> (роль: <?= $e($info['role']) ?>)
        <input type="hidden" name="csrf" value="<?= $e($csrf) ?>" />
        <input type="hidden" name="action" value="logout" />
        <input type="submit" value="Выйти" />
    </form>
    <?php
} else {
    ?>
    <h3>Вход для администратора</h3>
    <p>Добавлять и удалять пользователей может только администратор. Остальные могут только просматривать список.</p>
    <form method="post" action="index.php?id=users">
        Логин:<br /><input type="text" name="login" maxlength="20" /><br />
        Пароль:<br /><input type="password" name="password" /><br /><br />
        <input type="hidden" name="csrf" value="<?= $e($csrf) ?>" />
        <input type="hidden" name="action" value="login" />
        <input type="submit" value="Войти" />
    </form>
    <?php
}
?>

<h3>Список пользователей</h3>
<?php if (!$users) { ?>
    <p>Пользователей пока нет.</p>
<?php } else { ?>
    <table class="users">
        <tr>
            <th>№</th><th>Имя</th><th>Логин</th><th>Тип</th>
            <?php if ($isAdmin) { ?><th>Действие</th><?php } ?>
        </tr>
        <?php foreach ($users as $i => $u) { ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= $e($u->name) ?></td>
            <td><?= $e($u->login) ?></td>
            <td><?= $e(get_class($u)) ?></td>
            <?php if ($isAdmin) { ?>
            <td>
                <form method="post" action="index.php?id=users" class="inline"
                      onsubmit="return confirm('Удалить пользователя <?= $e($u->login) ?>?');">
                    <input type="hidden" name="csrf" value="<?= $e($csrf) ?>" />
                    <input type="hidden" name="action" value="delete" />
                    <input type="hidden" name="login" value="<?= $e($u->login) ?>" />
                    <input type="submit" value="Удалить" />
                </form>
            </td>
            <?php } ?>
        </tr>
        <?php } ?>
    </table>
<?php } ?>
<p>Всего обычных пользователей (User): <?= User::$count ?></p>

<?php if ($isAdmin) { ?>
<h3>Добавить пользователя</h3>
<form method="post" action="index.php?id=users">
    Имя:<br /><input type="text" name="name" maxlength="50" /><br />
    Логин (латиница, цифры, _):<br /><input type="text" name="login" maxlength="20" /><br />
    Пароль (от 4 символов):<br /><input type="password" name="password" /><br /><br />
    <input type="hidden" name="csrf" value="<?= $e($csrf) ?>" />
    <input type="hidden" name="action" value="add" />
    <input type="submit" value="Добавить" />
</form>
<?php } ?>

<p><a href="index.php?id=users-demo">Демонстрация лабораторной (задания 1–6)</a></p>
