<?php
// Задание 1. Создание класса и его экземпляров
class User {
    public $name;
    public $login;
    public $password;

    public function showInfo() {
        echo "Пользователь: {$this->name}, логин: {$this->login}, пароль: {$this->password}<br>";
    }
}

$user1 = new User();
$user1->name = "Иван Иванов";
$user1->login = "ivan";
$user1->password = "12345";

$user2 = new User();
$user2->name = "Петр Петров";
$user2->login = "petr";
$user2->password = "qwerty";

$user3 = new User();
$user3->name = "Сидор Сидоров";
$user3->login = "sidor";
$user3->password = "abcde";

$user1->showInfo();
$user2->showInfo();
$user3->showInfo();
