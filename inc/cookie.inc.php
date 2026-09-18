<?php
/* =========================================================
   Задание 1. Использование cookie
   Упражнение 1: Создание и чтение cookie
   ВАЖНО: файл должен подключаться ДО вывода любого HTML,
   иначе setcookie() не сработает (headers already sent).
   ========================================================= */

// Срок жизни кук - 1 год
define('COOKIE_LIFETIME', 60 * 60 * 24 * 365);

// true  - куки обновляются только один раз в день (последний пункт упражнения 2)
// false - куки обновляются при каждой перезагрузке страницы (удобно для проверки по [F5])
define('COOKIE_ONCE_A_DAY', true);

/* --- Счётчик посещений --------------------------------- */

// Целочисленная переменная со значением по умолчанию
$visitCounter = 0;

// Пришли ли куки visitCounter от пользователя?
if (isset($_COOKIE['visitCounter'])) {
    $visitCounter = (int) $_COOKIE['visitCounter'];
}

// Увеличиваем счётчик на единицу
$visitCounter++;

/* --- Дата последнего посещения ------------------------- */

// Строковая переменная со значением по умолчанию
$lastVisit = '';

// Пришли ли куки lastVisit от пользователя?
if (isset($_COOKIE['lastVisit'])) {
    // В куках хранится timestamp - форматируем его для вывода
    $lastVisit = date('d-m-Y H:i:s', (int) $_COOKIE['lastVisit']);
}

/* --- Установка кук ------------------------------------- */

// Условие: устанавливать куки только один раз в день
$needUpdate = !COOKIE_ONCE_A_DAY
    || !isset($_COOKIE['lastVisit'])
    || date('d-m-Y', (int) $_COOKIE['lastVisit']) != date('d-m-Y');

if ($needUpdate) {
    setcookie('visitCounter', $visitCounter, time() + COOKIE_LIFETIME, '/');
    setcookie('lastVisit',    time(),        time() + COOKIE_LIFETIME, '/');
}
