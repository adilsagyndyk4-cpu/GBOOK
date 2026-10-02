<?php
// classes/User.php
require_once __DIR__ . "/UserAbstract.php";

class User extends UserAbstract {
    public $name;
    public $login;
    public $password;
    public static $count = 0;
    // true — деструктор ничего не выводит (нужно для страницы сайта)
    public static $silent = false;

    public function __construct($name, $login, $password) {
        $this->name = $name;
        $this->login = $login;
        $this->password = $password;
        if (get_class($this) === "User") {
            self::$count++;
        }
    }

    public function showInfo() {
        echo "Пользователь: {$this->name}, логин: {$this->login}, пароль: {$this->password}<br>";
    }

    public function __destruct() {
        if (!self::$silent) {
            echo "Пользователь {$this->login} удален<br>";
        }
    }
}
