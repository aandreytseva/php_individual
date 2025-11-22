<?php
if (file_exists(__DIR__ . '/auth.php')) {
    require_once __DIR__ . '/auth.php';
}

if (!isset($activePage)) {
    $activePage = "";
}
function isActive(string $pageName, string $activePage): string {
    return $pageName === $activePage ? "nav__link nav__link--active" : "nav__link";
}
?>
<header class="header">
    <div class="header__brand">PromoService</div>
    <nav class="nav">
        <a class="<?= isActive('home', $activePage) ?>" href="index.php">Главная</a>
        <a class="<?= isActive('portfolio', $activePage) ?>" href="portfolio.php">Портфолио</a>
        <a class="<?= isActive('about', $activePage) ?>" href="about.php">О нас</a>
        <a class="<?= isActive('contact', $activePage) ?>" href="contact.php">Контакты</a>

        <?php if (function_exists('isLoggedIn') && isLoggedIn()): ?>
            <a class="<?= isActive('admin', $activePage) ?>" href="admin.php">📊 Админ</a>
            <a class="nav__link" href="logout.php" style="color: #ef4444;">🚪 Выход</a>
        <?php else: ?>
            <a class="<?= isActive('login', $activePage) ?>" href="login.php">🔐 Вход</a>
        <?php endif; ?>
    </nav>
</header>
