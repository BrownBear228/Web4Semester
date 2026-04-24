<?php
// config.php – параметры подключения и вспомогательные функции
defined('APP_ROOT') or die('Прямой доступ запрещён');

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'lab9');

/**
 * Подключение к БД (возвращает mysqli)
 */
function db_connect() {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$conn) {
        throw new Exception('Ошибка подключения: ' . mysqli_connect_error());
    }
    mysqli_set_charset($conn, 'utf8mb4');
    return $conn;
}

/**
 * Безопасный вывод в HTML
 */
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * Форматирование даты (YYYY-MM-DD → DD.MM.YYYY)
 */
function format_date($date) {
    if (empty($date) || $date === '0000-00-00') return '';
    return date('d.m.Y', strtotime($date));
}

/**
 * Полное ФИО
 */
function getFullName($row) {
    $parts = [$row['surname'], $row['name']];
    if (!empty($row['patronymic'])) $parts[] = $row['patronymic'];
    return implode(' ', $parts);
}

/**
 * Фамилия + инициалы (для списков редактирования/удаления)
 */
function getShortName($row) {
    $initials = '';
    if (!empty($row['name'])) $initials .= mb_substr($row['name'], 0, 1) . '.';
    if (!empty($row['patronymic'])) $initials .= mb_substr($row['patronymic'], 0, 1) . '.';
    return h($row['surname']) . ' ' . $initials;
}
?>
