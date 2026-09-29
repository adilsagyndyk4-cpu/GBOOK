<?php
/* Основные настройки */
define('DB_HOST', 'MySQL-8.4');
define('DB_LOGIN', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'gbook');

// Соединение с сервером БД и выбор базы данных
$link = mysqli_connect(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_NAME)
	or die('Ошибка соединения с БД: ' . mysqli_connect_error());
mysqli_set_charset($link, 'utf8');
/* Основные настройки */

/* Сохранение записи в БД */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// Принимаем и фильтруем данные
	$name  = mysqli_real_escape_string($link, trim(strip_tags($_POST['name'] ?? '')));
	$email = mysqli_real_escape_string($link, trim(strip_tags($_POST['email'] ?? '')));
	$msg   = mysqli_real_escape_string($link, trim(strip_tags($_POST['msg'] ?? '')));

	if ($name !== '' && $msg !== '') {
		$sql = "INSERT INTO msgs (name, email, msg) VALUES ('$name', '$email', '$msg')";
		if (!mysqli_query($link, $sql)) {
			echo '<p style="color:red">Ошибка при добавлении записи: ' . htmlspecialchars(mysqli_error($link)) . '</p>';
		}
	} else {
		echo '<p style="color:red">Заполните поля «Имя» и «Сообщение».</p>';
	}
}
/* Сохранение записи в БД */

/* Удаление записи из БД */
if (isset($_GET['del'])) {
	// Принимаем и фильтруем данные
	$del = abs((int)$_GET['del']);

	if ($del > 0) {
		$sql = "DELETE FROM msgs WHERE id = $del";
		if (!mysqli_query($link, $sql)) {
			echo '<p style="color:red">Ошибка при удалении записи: ' . htmlspecialchars(mysqli_error($link)) . '</p>';
		}
	}
}
/* Удаление записи из БД */
?>
<h3>Оставьте запись в нашей Гостевой книге</h3>

<form method="post" action="<?= htmlspecialchars(strtok($_SERVER['REQUEST_URI'], '&')) ?>">
Имя: <br /><input type="text" name="name" maxlength="25" /><br />
Email: <br /><input type="text" name="email" maxlength="50" /><br />
Сообщение: <br /><textarea name="msg"></textarea><br />

<br />

<input type="submit" value="Отправить!" />

</form>
<?php
/* Вывод записей из БД */
$sql = "SELECT id, name, email, msg, UNIX_TIMESTAMP(datetime) as dt
        FROM msgs
        ORDER BY id DESC";
$res = mysqli_query($link, $sql);

// Сохраняем результат выборки в массив
$rows = array();
if ($res) {
	while ($row = mysqli_fetch_assoc($res)) {
		$rows[] = $row;
	}
	mysqli_free_result($res);
} else {
	echo '<p style="color:red">Ошибка выборки: ' . htmlspecialchars(mysqli_error($link)) . '</p>';
}

// Закрываем соединение с сервером БД
mysqli_close($link);

echo '<p>Всего записей в гостевой книге: ' . count($rows) . '</p>';

foreach ($rows as $row) {
	$id    = (int)$row['id'];
	$name  = htmlspecialchars($row['name']);
	$email = htmlspecialchars($row['email']);
	$msg   = nl2br(htmlspecialchars($row['msg'] ?? ''));
	$date  = date('d-m-Y в H:i', $row['dt']);

	echo "<p>
<a href=\"mailto:$email\">$name</a> $date
написал<br />$msg
</p>
<p align=\"right\">
<a href=\"index.php?id=gbook&del=$id\">Удалить</a>
</p>";
}
/* Вывод записей из БД */
?>
