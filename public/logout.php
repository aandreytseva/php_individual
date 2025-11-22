<?php
/**
 * Скрипт выхода из системы
 */

require_once __DIR__ . "/../includes/config.php";
require_once __DIR__ . "/../includes/auth.php";

// Выполняем выход
logoutAdmin();

// Редирект на страницу входа
header("Location: login.php?logout=success");
exit;

