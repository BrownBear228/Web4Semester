<?php
// edit.php – список ссылок + форма редактирования
defined('APP_ROOT') or die('Прямой доступ запрещён');

$conn = db_connect();
$message = '';

// Обработка сохранения изменений
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_edit'])) {
    $id = (int)$_POST['id'];
    $surname     = mysqli_real_escape_string($conn, $_POST['surname']);
    $name        = mysqli_real_escape_string($conn, $_POST['name']);
    $patronymic  = mysqli_real_escape_string($conn, $_POST['patronymic']);
    $gender      = mysqli_real_escape_string($conn, $_POST['gender']);
    $birthdate   = mysqli_real_escape_string($conn, $_POST['birthdate']);
    $phone       = mysqli_real_escape_string($conn, $_POST['phone']);
    $address     = mysqli_real_escape_string($conn, $_POST['address']);
    $email       = mysqli_real_escape_string($conn, $_POST['email']);
    $comment     = mysqli_real_escape_string($conn, $_POST['comment']);

    $sql = "UPDATE friends SET 
            surname='$surname', name='$name', patronymic='$patronymic',
            gender='$gender', birthdate='$birthdate', phone='$phone',
            address='$address', email='$email', comment='$comment'
            WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        $message = '<div class="message success">✓ Данные успешно изменены.</div>';
    } else {
        $message = '<div class="message error">✗ Ошибка: ' . mysqli_error($conn) . '</div>';
    }
}

// Определяем текущую запись
$current_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($current_id <= 0) {
    $res = mysqli_query($conn, "SELECT id FROM friends ORDER BY id LIMIT 1");
    if ($row = mysqli_fetch_assoc($res)) $current_id = $row['id'];
}
$current = null;
if ($current_id > 0) {
    $res = mysqli_query($conn, "SELECT * FROM friends WHERE id=$current_id");
    $current = mysqli_fetch_assoc($res);
}

// Список всех записей для ссылок (сортировка по фамилии + имени)
$list_res = mysqli_query($conn, "SELECT id, surname, name, patronymic FROM friends ORDER BY surname, name");
?>

<div class="edit-container">
    <?= $message ?>
    <div class="links-list">
        <?php while ($row = mysqli_fetch_assoc($list_res)): ?>
            <?php if ($row['id'] == $current_id): ?>
                <span class="current-record"><?= getShortName($row) ?></span>
            <?php else: ?>
                <a href="?p=edit&id=<?= $row['id'] ?>"><?= getShortName($row) ?></a>
            <?php endif; ?>
        <?php endwhile; ?>
    </div>

    <?php if ($current): ?>
    <form method="post" action="?p=edit">
        <input type="hidden" name="id" value="<?= $current['id'] ?>">
        <div class="form-row"><label>Фамилия *</label><input type="text" name="surname" value="<?= h($current['surname']) ?>" required></div>
        <div class="form-row"><label>Имя *</label><input type="text" name="name" value="<?= h($current['name']) ?>" required></div>
        <div class="form-row"><label>Отчество</label><input type="text" name="patronymic" value="<?= h($current['patronymic']) ?>"></div>
        <div class="form-row">
            <label>Пол</label>
            <select name="gender">
                <option value="">—</option>
                <option value="М" <?= $current['gender'] == 'М' ? 'selected' : '' ?>>Мужской</option>
                <option value="Ж" <?= $current['gender'] == 'Ж' ? 'selected' : '' ?>>Женский</option>
            </select>
        </div>
        <div class="form-row"><label>Дата рождения</label><input type="date" name="birthdate" value="<?= h($current['birthdate']) ?>"></div>
        <div class="form-row"><label>Телефон</label><input type="text" name="phone" value="<?= h($current['phone']) ?>"></div>
        <div class="form-row"><label>Адрес</label><textarea name="address" rows="2"><?= h($current['address']) ?></textarea></div>
        <div class="form-row"><label>E‑mail</label><input type="email" name="email" value="<?= h($current['email']) ?>"></div>
        <div class="form-row"><label>Комментарий</label><textarea name="comment" rows="3"><?= h($current['comment']) ?></textarea></div>
        <div class="form-row"><button type="submit" name="submit_edit" class="btn">💾 Сохранить изменения</button></div>
    </form>
    <?php else: ?>
        <div class="message error">Записей в базе данных нет.</div>
    <?php endif; ?>
</div>
<?php mysqli_close($conn); ?>
