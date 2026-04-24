<?php
// index.php – единственный загружаемый документ
define('APP_ROOT', true);          // защита модулей от прямого вызова
require_once 'config.php';
require_once 'menu.php';

// Данные студента
$student_surname   = "Ионин";
$student_name      = "Михаил";
$student_patronymic= "Павлович";
$student_group     = "241-351";
$lab_number        = "9";
$lab_title         = "Записная книжка. Модули, базы данных.";

$page_title = "$student_surname $student_name $student_patronymic, $student_group, ЛР №$lab_number: $lab_title";

// Определяем активный модуль (по умолчанию viewer)
$p = $_GET['p'] ?? 'viewer';
$allowed = ['viewer', 'add', 'edit', 'delete'];
if (!in_array($p, $allowed)) $p = 'viewer';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($page_title) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="brand">
                <svg width="48" height="48" viewBox="0 0 100 100">
                    <rect x="25" y="55" width="50" height="15" fill="#fff" rx="4"/>
                    <rect x="35" y="25" width="6" height="30" fill="#fff"/>
                    <rect x="47" y="25" width="6" height="30" fill="#fff"/>
                    <rect x="59" y="25" width="6" height="30" fill="#fff"/>
                    <polygon points="25,25 50,5 75,25" fill="#fff"/>
                    <text x="50" y="72" font-size="12" text-anchor="middle" fill="#2c3e50" font-weight="bold">ИМ</text>
                </svg>
                <div class="brand-text">
                    <?= h("$student_surname $student_name $student_patronymic") ?><br>
                    <small><?= h("$student_group, ЛР №$lab_number") ?></small>
                </div>
            </div>
        </div>
    </header>

    <main>
        <?= getMenu() ?>   <!-- вывод основного меню + подменю -->

        <?php
        // подключение модуля контента
        if ($p === 'viewer') {
            require_once 'viewer.php';
            $sort = $_GET['sort'] ?? 'byid';
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 0;
            if ($page < 0) $page = 0;
            echo getFriendsList($sort, $page);
        } else {
            $module_file = $p . '.php';
            if (file_exists($module_file)) {
                include $module_file;
            } else {
                echo '<div class="message error">Модуль не найден.</div>';
            }
        }
        ?>
    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-info">
                <p><?= h("$student_surname $student_name $student_patronymic, $student_group") ?></p>
                <p>ЛР №<?= $lab_number ?></p>
            </div>
            <div class="footer-date">
                <p>Сформировано <?php date_default_timezone_set('Europe/Moscow'); echo date('d.m.Y в H:i:s'); ?></p>
            </div>
        </div>
    </footer>
</body>
</html>
