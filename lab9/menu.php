<?php
// menu.php – генерация главного меню и подменю сортировки
defined('APP_ROOT') or die('Прямой доступ запрещён');

function getMenu() {
    $p    = $_GET['p'] ?? 'viewer';
    $sort = $_GET['sort'] ?? 'byid';

    // Основные пункты меню
    $items = [
        'viewer' => 'Просмотр',
        'add'    => 'Добавление записи',
        'edit'   => 'Редактирование записи',
        'delete' => 'Удаление записи'
    ];

    $html = '<div class="main-menu">';
    foreach ($items as $key => $label) {
        $active = ($p === $key) ? ' class="selected"' : '';
        $html .= '<a href="?p=' . $key . '"' . $active . '>' . $label . '</a>';
    }
    $html .= '</div>';

    // Подменю сортировки (только для режима Просмотр)
    if ($p === 'viewer') {
        $sortOpts = [
            'byid'    => 'По умолчанию',
            'surname' => 'По фамилии',
            'birth'   => 'По дате рождения'
        ];
        $html .= '<div class="sub-menu">';
        foreach ($sortOpts as $key => $label) {
            $active = ($sort === $key) ? ' class="selected"' : '';
            $html .= '<a href="?p=viewer&sort=' . $key . '"' . $active . '>' . $label . '</a>';
        }
        $html .= '</div>';
    }

    return $html;
}
?>
