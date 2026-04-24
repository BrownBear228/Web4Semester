<?php
// delete.php – список ссылок и удаление записи
defined('APP_ROOT') or die('Прямой доступ запрещён');

$conn = db_connect();
$message = '';

// Обработка удаления
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    $res = mysqli_query($conn, "SELECT surname FROM friends WHERE id=$id");
    if ($row = mysqli_fetch_assoc($res)) {
        $surname = h($row['surname']);
        $sql = "DELETE FROM friends WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            $message = '<div class="message success">✓ Запись с фамилией «' . $surname . '» удалена.</div>';
        } else {
            $message = '<div class="message error">✗ Ошибка при удалении.</div>';
        }
    } else {
        $message = '<div class="message error">Запись не найдена.</div>';
    }
}

// Список всех записей (фамилия + инициалы)
$list_res = mysqli_query($conn, "SELECT id, surname, name, patronymic FROM friends ORDER BY surname, name");
?>

<div class="delete-container">
    <?= $message ?>
    <div class="links-list">
        <?php while ($row = mysqli_fetch_assoc($list_res)): ?>
            <a href="?p=delete&delete_id=<?= $row['id'] ?>"><?= getShortName($row) ?></a>
        <?php endwhile; ?>
    </div>
    <?php if (mysqli_num_rows($list_res) == 0): ?>
        <div class="message info">Нет записей для удаления.</div>
    <?php endif; ?>
</div>
<?php mysqli_close($conn); ?>
