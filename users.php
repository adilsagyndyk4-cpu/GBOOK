<?php
// Задание 6. Использование автозагрузки классов
spl_autoload_register(function ($class) {
    require __DIR__ . "/classes/{$class}.php";
});

$user1 = new User("Иван Иванов", "ivan", "12345");
$user2 = new User("Петр Петров", "petr", "qwerty");
$user3 = new User("Сидор Сидоров", "sidor", "abcde");

$user1->showInfo();
$user2->showInfo();
$user3->showInfo();

$user = new SuperUser("Админ Админов", "admin", "admin123", "admin");
$user->showInfo();

echo "<pre>";
print_r($user->getInfo());
echo "</pre>";

$result = $user->auth("admin", "admin123");
echo "Авторизация (admin/admin123): " . ($result ? "true" : "false") . "<br>";

$result2 = $user->auth("admin", "wrong");
echo "Авторизация (admin/wrong): " . ($result2 ? "true" : "false") . "<br>";

echo "Всего обычных пользователей: " . User::$count . "<br>";
echo "Всего супер-пользователей: " . SuperUser::$countSuper . "<br>";

