<?php require 'config.php'; ?>
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
    <div class="d-flex justify-content-between flex-wrap">
        <h1>Каталог номеров</h1>
        <form method="get" class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                Категории
            </button>
            <ul class="dropdown-menu p-2">
                <li>
                    <label><input type="radio" name="category" value="Стандарт"> Стандартный</label>
                </li>
                <li>
                    <label><input type="radio" name="category" value="Студия"> Студия</label>
                </li>
                <li>
                    <label><input type="radio" name="category" value="Люкс"> Люкс</label>
                </li>
            </ul>
            <button type="submit" class="btn btn-primary my-1">Применить</button>
            <a href="index.php" class="btn btn-danger my-1">Сбросить фильтр</a>
        </form>
    </div>

    <div class="d-flex justify-content-around flex-wrap align-items-center">
        <?php
        if (!empty($_GET['category'])) {
            $stmt = $pdo->prepare("SELECT * FROM rooms WHERE category = ?");
            $stmt->execute([$_GET['category']]);
        } else {
            $stmt = $pdo->query("SELECT * FROM rooms");
        }
        $rooms = $stmt->fetchAll();

        foreach ($rooms as $room):
        ?>
        <div class="card">
            <img src="img/<?= htmlspecialchars($room['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($room['category']) ?>">
            <div class="card-body">
                <h3>Категория: <?= htmlspecialchars($room['category']) ?></h3>
                <h5>Цена: <?= (int)$room['price'] ?> ₽ / сутки</h5>
                <h5>Мест: <?= (int)$room['capacity'] ?>, площадь: <?= htmlspecialchars($room['area']) ?> м²</h5>
                <h5>Характеристики:</h5>
                <ul class="list-group">
                    <li class="list-group-item"><?= htmlspecialchars($room['amenities']) ?></li>
                </ul>
            </div>
            <div class="d-grid gap-2">
                <a href="order.php?room_id=<?= (int)$room['id'] ?>" class="btn btn-success">Забронировать</a>
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