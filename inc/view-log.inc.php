<?php
/* =========================================================
   Задание 2. Упражнение 3: Вывод данных из файла журнала
   ========================================================= */

$logFile = __DIR__ . '/../log/' . PATH_LOG;

// Существует ли файл журнала?
if (!file_exists($logFile) || filesize($logFile) == 0) {
    echo '<p>Журнал посещений пока пуст.</p>';
} else {
    // Получаем всё содержимое файла в виде массива строк
    $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Свежие записи - сверху
    $lines = array_reverse($lines);

    echo '<p>Всего записей: ' . count($lines) . '</p>';
    echo '<ol>';

    foreach ($lines as $line) {
        // Разбираем строку по разделителю
        list($dt, $page, $ref) = array_pad(explode('|', $line, 3), 3, '');

        $dt   = date('d-m-Y H:i:s', (int) $dt);
        $page = htmlspecialchars($page, ENT_QUOTES, 'UTF-8');
        $ref  = $ref === '' ? 'прямой заход' : htmlspecialchars($ref, ENT_QUOTES, 'UTF-8');

        echo '<li>' . $dt . ' - ' . $page . ' -&gt; ' . $ref . '</li>';
    }

    echo '</ol>';
}
