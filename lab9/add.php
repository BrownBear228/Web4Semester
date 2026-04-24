<?php
// add.php – форма добавления + обработка POST
defined('APP_ROOT') or die('Прямой доступ запрещён');

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_add'])) {
    try {
        $conn = db_connect();

        // Безопасное экранирование (mysqli_real_escape_string)
        $surname     = mysqli_real_escape_string($conn, $_POST['surname']);
        $name        = mysqli_real_escape_string($conn, $_POST['name']);
        $patronymic  = mysqli_real_escape_string($conn, $_POST['patronymic']);
        $gender      = mysqli_real_escape_string($conn, $_POST['gender']);
        $birthdate   = mysqli_real_escape_string($conn, $_POST['birthdate']);
        $phone       = mysqli_real_escape_string($conn, $_POST['phone']);
        $address     = mysqli_real_escape_string($conn, $_POST['address']);
        $email       = mysqli_real_escape_string($conn, $_POST['email']);
        $comment     = mysqli_real_escape_string($conn, $_POST['comment']);

        // Валидация обязательных полей
        if (empty($surname) || empty($name)) {
            throw new Exception('Фамилия и имя обязательны для заполнения.');
        }

        $sql = "INSERT INTO friends (surname, name, patronymic, gender, birthdate, phone, address, email, comment)
                VALUES ('$surname', '$name', '$patronymic', '$gender', '$birthdate', '$phone', '$address', '$email', '$comment')";

        if (mysqli_query($conn, $sql)) {
            $message = '<div class="message success">✓ Запись успешно добавлена.</div>';
        } else {
            throw new Exception('Ошибка при добавлении: ' . mysqli_error($conn));
        }
        mysqli_close($conn);
    } catch (Exception $e) {
        $message = '<div class="message error">✗ Ошибка: ' . h($e->getMessage()) . '</div>';
    }
}
?>

<div class="form-card">
    <?= $message ?>
    <form method="post" action="?p=add">
        <div class="form-row"><label>Фамилия *</label><input type="text" name="surname" required></div>
        <div class="form-row"><label>Имя *</label><input type="text" name="name" required></div>
        <div class="form-row"><label>Отчество</label><input type="text" name="patronymic"></div>
        <div class="form-row">
            <label>Пол</label>
            <select name="gender">
                <option value="">—</option>
                <option value="М">Мужской</option>
                <option value="Ж">Женский</option>
            </select>
        </div>
        <div class="form-row"><label>Дата рождения</label><input type="date" name="birthdate"></div>
        <div class="form-row"><label>Телефон</label><input type="text" name="phone" placeholder="+7 ..."></div>
        <div class="form-row"><label>Адрес</label><textarea name="address" rows="2"></textarea></div>
        <div class="form-row"><label>E‑mail</label><input type="email" name="email"></div>
        <div class="form-row"><label>Комментарий</label><textarea name="comment" rows="3"></textarea></div>
        <div class="form-row"><button type="submit" name="submit_add" class="btn">➕ Добавить запись</button></div>
    </form>
</div>
