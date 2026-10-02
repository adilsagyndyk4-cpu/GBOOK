<?php
// classes/SuperUser.php
require_once __DIR__ . "/User.php";
require_once __DIR__ . "/ISuperUser.php";
require_once __DIR__ . "/IAuthorizeUser.php";

class SuperUser extends User implements ISuperUser, IAuthorizeUser {
    public $role;
    public static $countSuper = 0;

    public function __construct($name, $login, $password, $role) {
        parent::__construct($name, $login, $password);
        $this->role = $role;
        self::$countSuper++;
    }

    public function showInfo() {
        echo "Пользователь: {$this->name}, логин: {$this->login}, пароль: {$this->password}, роль: {$this->role}<br>";
    }

    public function getInfo() {
        return array(
            "name" => $this->name,
            "login" => $this->login,
            "password" => $this->password,
            "role" => $this->role
        );
    }

    public function auth($login, $password) {
        if ($login === $this->login && $password === $this->password) {
            return true;
        }
        return false;
    }
}
