<?php
// Задание 4. Абстрактные классы и интерфейсы
abstract class UserAbstract {
    abstract public function showInfo();
}

class User extends UserAbstract {
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

interface ISuperUser {
    public function getInfo();
}

interface IAuthorizeUser {
    public function auth($login, $password);
}

class SuperUser extends User implements ISuperUser, IAuthorizeUser {
    public $role;

    public function __construct($name, $login, $password, $role) {
        parent::__construct($name, $login, $password);
        $this->role = $role;
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
