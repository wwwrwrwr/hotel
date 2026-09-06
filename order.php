<?php
require 'config.php';

$success = false;
$errors = [];
$room_id = isset($_GET['room_id']) ? (int)$_GET['room_id'] : (isset($_POST['room_id']) ? (int)$_POST['room_id'] : 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $last_name = trim($_POST['last_name'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $date_in = trim($_POST['date_in'] ?? '');
    $date_out = trim($_POST['date_out'] ?? '');
    $room_id = (int)($_POST['room_id'] ?? 0);

    $nameRe = '/^[А-Яа-яЁё\s\-]+$/u';

    if (!preg_match($nameRe, $first_name)) $errors[] = 'Некорректное имя';
    if (!preg_match($nameRe, $last_name)) $errors[] = 'Некорректная фамилия';
    if (!preg_match('/^\+?\d[\d\s\-\(\)]{9,}$/', $phone)) $errors[] = 'Некорректный телефон';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Некорректный email';
    if (!$date_in || !$date_out) $errors[] = 'Укажите даты заезда и выезда';
    if ($date_in && $date_out && $date_in >= $date_out) $errors[] = 'Дата выезда должна быть позже даты заезда';
    if (!$room_id) $errors[] = 'Не выбран номер';

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO bookings (room_id, last_name, first_name, phone, email, date_in, date_out) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$room_id, $last_name, $first_name, $phone, $email, $date_in, $date_out]);
        $success = true;
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
    <title>Document</title>
</head>
<body class="container">
<header class="d-flex flex-wrap justify-content-center py-3">
    <a href="index.php"
       class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
        <span class="fs-4 mx-2 fw-medium">Светлые Сны</span>
    </a>
    <ul class="nav">
        <li class="nav-item"><a href="#" class="nav-link">Приезжайте как гости, уезжайте как друзья!</a></li>
    </ul>
</header>

<?php if ($success): ?>
<div class="alert alert-success text-center" role="alert">
    Заявка успешно отправлена!
</div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger" role="alert">
    <ul class="mb-0">
        <?php foreach ($errors as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<main>
    <div class="d-flex justify-content-between flex-wrap align-items-center">
        <h1>Бронирование номера</h1>
    </div>

    <form class="row g-3 my-2" method="post">
        <input type="hidden" name="room_id" value="<?= (int)$room_id ?>">
        <div class="col-md-4">
            <label for="first_name" class="form-label">Имя</label>
            <input type="text" class="form-control" id="first_name" name="first_name" required>
        </div>
        <div class="col-md-4">
            <label for="last_name" class="form-label">Фамилия</label>
            <input type="text" class="form-control" id="last_name" name="last_name" required>
        </div>
        <div class="col-md-4">
            <label for="phone" class="form-label">Телефон</label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="+7(900)000-00-00" required>
        </div>
        <div class="col-md-6">
            <label for="email" class="form-label">Почта</label>
            <input type="text" class="form-control" id="email" name="email" required>
        </div>
        <div class="col-md-3">
            <label for="date_in" class="form-label">Дата заезда</label>
            <input type="date" class="form-control" id="date_in" name="date_in" required>
        </div>
        <div class="col-md-3">
            <label for="date_out" class="form-label">Дата выезда</label>
            <input type="date" class="form-control" id="date_out" name="date_out" required>
        </div>
        <div class="d-grid gap-2">
            <button class="btn btn-primary" type="submit">Отправить заявку</button>
        </div>
    </form>
</main>

<footer class="py-2 my-2">
    <ul class="nav justify-content-between align-items-center">
        <li class="nav-item"><a href="#" class="nav-link text-body-secondary">ул. г.Москва, ул. Ивовая, 48</a></li>
        <li class="nav-item"><a href="#" class="nav-link text-body-secondary">Время работы: Пн-Пт, с 8:00-17:00</a></li>
        <li class="nav-item"><a href="tel:88005553535" class="nav-link text-body-secondary">тел. 8 (800) 555 - 35 - 35</a></li>
        <li class="nav-item"><a href="mailto:обращения@СветлыеСны.рф" class="nav-link text-body-secondary">Email: обращения@СветлыеСны.рф</a></li>
    </ul>
</footer>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/jquery-3.7.1.slim.min.js"></script>
<script src="js/jquery.inputmask.min.js"></script>
<script src="js/index.js"></script>
</body>
</html>