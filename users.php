<?php
// Задание 2. Использование конструктора и деструктора
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

$user1 = new User("Иван Иванов", "ivan", "12345");
$user2 = new User("Петр Петров", "petr", "qwerty");
$user3 = new User("Сидор Сидоров", "sidor", "abcde");

$user1->showInfo();
$user2->showInfo();
$user3->showInfo();
