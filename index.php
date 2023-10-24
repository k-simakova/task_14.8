<?php

require_once('functions.php');

// Проверяем, авторизован ли пользователь
$user = getCurrentUser();
if (!$user) {
    header('Location: login.php');
    exit();
}

// Записываем время входа пользователя
//session_start();
if (!isset($_SESSION['login_time'])) {
    $_SESSION['login_time'] = time();
}

// Вычисляем время до истечения персональной скидки
$discount_time = $_SESSION['login_time'] + 24 * 60 * 60;
$discount_left = $discount_time - time();
$discount_left_str = sprintf('%02d:%02d:%02d', $discount_left / 3600, ($discount_left % 3600) / 60, $discount_left % 60);


$next_birthday = null; // Предварительное объявление переменной

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $birth_date = new DateTime($_POST['birth_date']);
    $_SESSION['birth_date'] = $birth_date;
}

// Проверяем наличие сохраненной даты рождения в сессии
if (isset($_SESSION['birth_date'])) {
    $now = new DateTime(date('Y-m-d'));
    $birth_date = $_SESSION['birth_date'];

    // Устанавливаем текущий год для даты рождения
    $birth_date->setDate($now->format('Y'), $birth_date->format('m'), $birth_date->format('d'));

    // Создаем объект $next_birthday
    $next_birthday = new DateTime($birth_date->format('Y') . '-' . $birth_date->format('m-d'));

    // Если день уже прошел в текущем году, добавляем год для следующего года
    if ($next_birthday < $now) {
        $next_birthday->modify('+1 year');
    }

    // Проверяем, если день рождения сегодня
    $is_today_birthday = $next_birthday->format('m-d') === $now->format('m-d');

    if ($is_today_birthday) {
        $days_left = 0;
    } else {
        $interval = $now->diff($next_birthday);
        $days_left = $interval->days;
    }
} else {
    $days_left = null;
}


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link href="style.css" rel="stylesheet" type="text/css">
    <title>SPA-салон</title>
</head>
<body>
    <h1>Добро пожаловать, <?= $user ?>!</h1>
    <img src="https://cdn.stocksnap.io/img-thumbs/960w/beauty-skincare_BSXHIPGGHI.jpg">
    <p>Мы рады приветсвовать Вас на нашей странице.</p>
    <blockquote class="blockquote-N">
        <p>Ваша естественная красота - это лучшый выбор!</p>
    </blockquote>
    <p>Мы всегда руководствуемся нашим девизом. Именно поэтому мы предлагаем услуги, которые помогут вам раскрыть и подчекнуть вашу естесвенную красоту. Наша команда профессионалов знает, как сделать так, чтобы вы чувствовали себя уверенно без лишних усилий. Именно поэтому только сегодня мы предлагаем вам скидку 10% на услугу "Расслабляющий массаж всего тела". </p>
    <p>До истечения персональной скидки осталось: <?= $discount_left_str ?></p>
    <img src="https://cdn.stocksnap.io/img-thumbs/960w/smiling-woman_MYUJO5PVDC.jpg">
    <p>Весь месяц акция на чистку лица
        <span>7 999 &#8381;</span>
        <del>9 999 &#8381;</del>
    </p>
    <?php if ($days_left !== null):
?>
    <?php if ($days_left == 0): ?>
        <p>Сегодня ваш день рождения! Поздравляем!</p>
        <p>Ваша персональная скидка: 15%</p>
    <?php else: ?>
        <p>До вашего дня рождения осталось: <?= $days_left ?> дней</p>
    <?php endif; ?>
    <?php else: ?>
        <form method="post">
            <div>
                <p>Наш салон заботится о своих клиентах, поэтому всем нашим именинникам мы дарим скидку 15% на все услуги салона.</p>
                <label>Введите дату рождения:</label>
                <input type="date" name="birth_date">
            </div>
            <button type="submit">Отправить</button>
        </form>
    <?php endif; ?>
    <a href="logout.php">Выход</a>
</body>
</html>