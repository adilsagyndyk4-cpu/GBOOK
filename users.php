<?php
// Задание 3. Реализация наследования классов
class User {
    public $name;
    public $login;
    public $password;

    public function __construct($name, $login, $password) {
        $this->name = $name;
        $this->login = $login;
        $this->password = $password;
    }

    public function showInfo() {
        echo "Пользователь: {$this->name}, логин: {$this->login}, пароль: {$this->password}<br>";
    }

    public function __destruct() {
        echo "Пользователь {$this->login} удален<br>";
    }
}

class SuperUser extends User {
    public $role;

    public function __construct($name, $login, $password, $role) {
        parent::__construct($name, $login, $password);
        $this->role = $role;
    }

    public function showInfo() {
        echo "Пользователь: {$this->name}, логин: {$this->login}, пароль: {$this->password}, роль: {$this->role}<br>";
    }
}

$user1 = new User("Иван Иванов", "ivan", "12345");
$user2 = new User("Петр Петров", "petr", "qwerty");
$user3 = new User("Сидор Сидоров", "sidor", "abcde");

$user1->showInfo();
$user2->showInfo();
$user3->showInfo();

$user = new SuperUser("Админ Админов", "admin", "admin123", "admin");
$user->showInfo();
