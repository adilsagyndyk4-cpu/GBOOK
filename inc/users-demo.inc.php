<?php
// Демонстрация заданий 1–6: код из users.php.
// Обёрнуто в функцию, чтобы деструкторы печатали сообщения внутри блока контента.
function showUsersDemo()
{
    require_once __DIR__ . '/../users.php';
}
showUsersDemo();
?>
<p><a href="index.php?id=users">← К списку пользователей</a></p>
