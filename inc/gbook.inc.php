<?php
/* Основные настройки */
define('DB_HOST', 'MySQL-8.4');
define('DB_LOGIN', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'gbook');

// Отключаем исключения mysqli: ошибки обрабатываем сами через проверки ниже
mysqli_report(MYSQLI_REPORT_OFF);

// Соединение с сервером БД и выбор базы данных
$link = mysqli_connect(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_NAME)
	or die('Ошибка соединения с БД: ' . mysqli_connect_error());
mysqli_set_charset($link, 'utf8');
/* Основные настройки */

/* Сохранение записи в БД */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// Принимаем данные (экранировать для SQL не нужно: это делают подготовленные запросы)
	$name  = trim($_POST['name'] ?? '');
	$email = trim($_POST['email'] ?? '');
	$msg   = trim($_POST['msg'] ?? '');

	if ($name !== '' && $msg !== '') {
		// Подготовленный запрос: вместо значений стоят метки ?
		$stmt = mysqli_prepare($link, "INSERT INTO msgs (name, email, msg) VALUES (?, ?, ?)");
		if ($stmt) {
			// Привязываем значения к меткам: sss = три строки
			mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $msg);
			if (!mysqli_stmt_execute($stmt)) {
				echo '<p style="color:red">Ошибка при добавлении записи: ' . htmlspecialchars(mysqli_stmt_error($stmt)) . '</p>';
			}
			mysqli_stmt_close($stmt);
		} else {
			echo '<p style="color:red">Ошибка подготовки запроса: ' . htmlspecialchars(mysqli_error($link)) . '</p>';
		}
	} else {
		echo '<p style="color:red">Заполните поля «Имя» и «Сообщение».</p>';
	}
}
/* Сохранение записи в БД */

/* Удаление записи из БД */
if (isset($_GET['del'])) {
	// Принимаем данные: id должен быть целым положительным числом
	$del = abs((int)$_GET['del']);

	if ($del > 0) {
		$stmt = mysqli_prepare($link, "DELETE FROM msgs WHERE id = ?");
		if ($stmt) {
			// i = целое число
			mysqli_stmt_bind_param($stmt, 'i', $del);
			if (!mysqli_stmt_execute($stmt)) {
				echo '<p style="color:red">Ошибка при удалении записи: ' . htmlspecialchars(mysqli_stmt_error($stmt)) . '</p>';
			}
			mysqli_stmt_close($stmt);
		} else {
			echo '<p style="color:red">Ошибка подготовки запроса: ' . htmlspecialchars(mysqli_error($link)) . '</p>';
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
// В этом запросе нет данных от пользователя, поэтому обычного mysqli_query достаточно
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