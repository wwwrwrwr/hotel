<?php
session_start();
require 'config.php';

if (empty($_SESSION['is_admin'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($action === 'approve') {
        $stmt = $pdo->prepare("UPDATE bookings SET status = 'Одобрена' WHERE id = ?");
        $stmt->execute([$id]);
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ?");
        $stmt->execute([$id]);
    }
    header('Location: admin.php');
    exit;
}

$bookings = $pdo->query("
    SELECT b.*, r.category, r.price 
    FROM bookings b 
    JOIN rooms r ON b.room_id = r.id 
    ORDER BY b.created_at DESC
")->fetchAll();
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
<main>
    <div class="d-flex justify-content-between flex-wrap align-items-center">
        <h1>Панель администратора</h1>
    </div>
    <div class="d-flex justify-content-around flex-wrap align-items-center">
        <?php foreach ($bookings as $b): ?>
        <div class="card">
            <div class="card-body">
                <h5>Фамилия: <?= htmlspecialchars($b['last_name']) ?></h5>
                <h5>Имя: <?= htmlspecialchars($b['first_name']) ?></h5>
                <h5>Телефон: <?= htmlspecialchars($b['phone']) ?></h5>
                <h5>Номер: <?= htmlspecialchars($b['category']) ?></h5>
                <h5>Статус: <?= htmlspecialchars($b['status']) ?></h5>
                <ul class="list-group">
                    <li class="list-group-item">Дата заезда: <?= htmlspecialchars($b['date_in']) ?></li>
                    <li class="list-group-item">Дата выезда: <?= htmlspecialchars($b['date_out']) ?></li>
                </ul>
            </div>
            <div class="d-grid gap-2">
                <form method="post">
                    <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
                    <input type="hidden" name="action" value="approve">
                    <button type="submit" class="btn btn-success w-100">Одобрить</button>
                </form>
                <form method="post">
                    <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" class="btn btn-danger w-100">Удалить</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
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
</body>
</html>