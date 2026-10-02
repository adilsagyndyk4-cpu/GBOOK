<?php
// classes/UserRepository.php
// Хранилище пользователей сайта. Данные лежат в файле data/users.data.php:
// первая строка файла (php exit) не даёт открыть его через браузер, дальше идёт JSON.
// Пароли хранятся в виде хэшей (password_hash), а не открытым текстом.
class UserRepository {
    private $file;

    public function __construct($file) {
        $this->file = $file;
    }

    // Все пользователи в виде массива объектов User
    public function all() {
        $users = array();
        foreach ($this->read() as $row) {
            $users[] = new User($row['name'], $row['login'], $row['password']);
        }
        return $users;
    }

    public function exists($login) {
        foreach ($this->read() as $row) {
            if (strcasecmp($row['login'], $login) === 0) {
                return true;
            }
        }
        return false;
    }

    // Добавление. Возвращает null при успехе или текст ошибки
    public function add($name, $login, $password) {
        $name = trim($name);
        $login = trim($login);

        if ($name === '' || preg_match_all('/./us', $name) > 50) {
            return 'Имя: от 1 до 50 символов.';
        }
        if (!preg_match('/^[A-Za-z0-9_]{3,20}$/', $login)) {
            return 'Логин: 3–20 символов, только латинские буквы, цифры и _.';
        }
        if (strlen($password) < 4) {
            return 'Пароль: не короче 4 символов.';
        }
        if (strcasecmp($login, ADMIN_LOGIN) === 0 || $this->exists($login)) {
            return 'Пользователь с логином «' . $login . '» уже существует.';
        }

        $rows = $this->read();
        $rows[] = array(
            'name' => $name,
            'login' => $login,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        );
        return $this->write($rows) ? null : 'Не удалось сохранить данные (нет прав на запись в папку data).';
    }

    // Удаление по логину. true — пользователь был найден и удалён
    public function delete($login) {
        $rows = $this->read();
        $left = array();
        foreach ($rows as $row) {
            if ($row['login'] !== $login) {
                $left[] = $row;
            }
        }
        if (count($left) === count($rows)) {
            return false;
        }
        return $this->write($left);
    }

    private function read() {
        if (!is_file($this->file)) {
            $this->write($this->seed());
        }
        $raw = (string)file_get_contents($this->file);
        $pos = strpos($raw, "\n");
        $data = json_decode($pos === false ? '' : substr($raw, $pos + 1), true);
        return is_array($data) ? $data : array();
    }

    private function write($rows) {
        $dir = dirname($this->file);
        if (!is_dir($dir) && !@mkdir($dir, 0775, true)) {
            return false;
        }
        $json = json_encode(array_values($rows), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        return @file_put_contents($this->file, "<?php exit; ?>\n" . $json, LOCK_EX) !== false;
    }

    // Начальные пользователи при первом запуске
    private function seed() {
        $demo = array(
            array('Иван Иванов', 'ivan', '12345'),
            array('Петр Петров', 'petr', 'qwerty'),
            array('Сидор Сидоров', 'sidor', 'abcde'),
        );
        $rows = array();
        foreach ($demo as $d) {
            $rows[] = array('name' => $d[0], 'login' => $d[1], 'password' => password_hash($d[2], PASSWORD_DEFAULT));
        }
        return $rows;
    }
}
