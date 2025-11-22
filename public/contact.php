<?php
$pageTitle = "Контакты и заявка";
$activePage = "contact";

require_once __DIR__ . "/../includes/config.php";

$name = "";
$email = "";
$service = "";
$message = "";
$success = false;
$errors = [];
$dbError = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $service = trim($_POST["service"] ?? "");
    $message = trim($_POST["message"] ?? "");

    if ($name === "") {
        $errors["name"] = "Введите ваше имя";
    } elseif (strlen($name) < 2) {
        $errors["name"] = "Имя должно содержать минимум 2 символа";
    }

    if ($email === "") {
        $errors["email"] = "Введите e-mail";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Некорректный формат e-mail";
    }

    if ($service === "") {
        $errors["service"] = "Укажите интересующую услугу";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO contact_requests (name, email, service, message) VALUES (?, ?, ?, ?)";
        $result = executeQuery($sql, [$name, $email, $service, $message]);

        if ($result) {
            $success = true;
            $name = "";
            $email = "";
            $service = "";
            $message = "";
        } else {
            $dbError = true;
        }
    }
}

include __DIR__ . "/../includes/header.php";
?>

<section class="section">
    <h1>Связаться с нами</h1>
    <p>Оставьте заявку, и мы свяжемся с вами для обсуждения деталей.</p>

    <?php if ($success): ?>
        <div class="alert alert--success">
            Спасибо, ваша заявка успешно отправлена и сохранена в базе данных! Мы свяжемся с вами по указанному e-mail.
        </div>
    <?php endif; ?>

    <?php if ($dbError): ?>
        <div class="alert alert--error">
            Ошибка при сохранении заявки. Пожалуйста, попробуйте позже или свяжитесь с нами по телефону.
        </div>
    <?php endif; ?>

    <form class="form" method="post" action="contact.php">
        <div class="form__group">
            <label for="name">Имя *</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>">
            <?php if (isset($errors["name"])): ?>
                <div class="form__error"><?= htmlspecialchars($errors["name"]) ?></div>
            <?php endif; ?>
        </div>

        <div class="form__group">
            <label for="email">E-mail *</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>">
            <?php if (isset($errors["email"])): ?>
                <div class="form__error"><?= htmlspecialchars($errors["email"]) ?></div>
            <?php endif; ?>
        </div>

        <div class="form__group">
            <label for="service">Интересующая услуга *</label>
            <input type="text" id="service" name="service" placeholder="Например: продвижение сайта" value="<?= htmlspecialchars($service) ?>">
            <?php if (isset($errors["service"])): ?>
                <div class="form__error"><?= htmlspecialchars($errors["service"]) ?></div>
            <?php endif; ?>
        </div>

        <div class="form__group">
            <label for="message">Комментарий</label>
            <textarea id="message" name="message" rows="4"><?= htmlspecialchars($message) ?></textarea>
        </div>

        <button class="btn" type="submit">Отправить заявку</button>
    </form>
</section>

<section class="section section--muted">
    <h2>Контактная информация</h2>
    <p>E-mail: demo@example.com</p>
    <p>Телефон: +373 600 00 000</p>
</section>

<?php include __DIR__ . "/../includes/footer.php"; ?>
