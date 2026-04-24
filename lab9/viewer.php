<?php
// viewer.php – вывод таблицы с пагинацией и сортировкой
defined('APP_ROOT') or die('Прямой доступ запрещён');

function getFriendsList($sort, $page) {
    $conn = db_connect();
    $limit = 10;
    $offset = $page * $limit;

    // Формируем ORDER BY в зависимости от типа сортировки
    switch ($sort) {
        case 'surname': $order_by = "surname, name, patronymic"; break;
        case 'birth':   $order_by = "birthdate"; break;
        default:        $order_by = "id";
    }

    // Общее количество записей
    $total_res = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM friends");
    if (!$total_res) {
        mysqli_close($conn);
        return '<div class="message error">Ошибка подсчёта записей.</div>';
    }
    $total = mysqli_fetch_assoc($total_res)['cnt'];
    $pages = ceil($total / $limit);

    if ($total == 0) {
        mysqli_close($conn);
        return '<div class="message info">В таблице пока нет данных.</div>';
    }

    // Корректировка номера страницы
    if ($page >= $pages) $page = $pages - 1;
    if ($page < 0) $page = 0;
    $offset = $page * $limit;

    // Основной запрос
    $sql = "SELECT * FROM friends ORDER BY $order_by LIMIT $offset, $limit";
    $res = mysqli_query($conn, $sql);
    if (!$res) {
        mysqli_close($conn);
        return '<div class="message error">Ошибка запроса: ' . mysqli_error($conn) . '</div>';
    }

    // Генерация таблицы
    $html = '<div class="table-wrapper">';
    $html .= '<table class="data-table">
                <thead>
                    <tr><th>ID</th><th>Фамилия</th><th>Имя</th><th>Отчество</th>
                     <th>Пол</th><th>Дата рождения</th><th>Телефон</th>
                     <th>Адрес</th><th>E‑mail</th><th>Комментарий</th></tr>
                </thead>
                <tbody>';

    while ($row = mysqli_fetch_assoc($res)) {
        $html .= '<tr>
                    <td>' . h($row['id']) . '</td>
                    <td>' . h($row['surname']) . '</td>
                    <td>' . h($row['name']) . '</td>
                    <td>' . h($row['patronymic']) . '</td>
                    <td>' . h($row['gender']) . '</td>
                    <td>' . format_date($row['birthdate']) . '</td>
                    <td>' . h($row['phone']) . '</td>
                    <td>' . h($row['address']) . '</td>
                    <td>' . h($row['email']) . '</td>
                    <td>' . h($row['comment']) . '</td>
                </tr>';
    }
    $html .= '</tbody></table></div>';
    mysqli_free_result($res);

    // Пагинация (с рамкой при наведении)
    if ($pages > 1) {
        $html .= '<div class="pagination">';
        for ($i = 0; $i < $pages; $i++) {
            if ($i == $page) {
                $html .= '<span class="active-page">' . ($i + 1) . '</span>';
            } else {
                $url = "?p=viewer&sort=" . urlencode($sort) . "&page=" . $i;
                $html .= '<a href="' . $url . '">' . ($i + 1) . '</a>';
            }
        }
        $html .= '</div>';
    }

    mysqli_close($conn);
    return $html;
}
?>
