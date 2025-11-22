<?php
$pageTitle = "Админ-панель - Заявки";
$activePage = "admin";

require_once __DIR__ . "/../includes/config.php";
require_once __DIR__ . "/../includes/auth.php";

requireAuth();

// Фильтры
$filterStatus = $_GET['status'] ?? '';
$filterService = $_GET['service'] ?? '';

// Формируем SQL с фильтрами
$sql = "SELECT * FROM contact_requests WHERE 1=1";
$params = [];

if (!empty($filterStatus)) {
    $sql .= " AND status = ?";
    $params[] = $filterStatus;
}

if (!empty($filterService)) {
    $sql .= " AND service LIKE ?";
    $params[] = "%{$filterService}%";
}

$sql .= " ORDER BY created_at DESC";

$stmt = executeQuery($sql, $params);
$requests = $stmt ? $stmt->fetchAll() : [];

// Получаем уникальные услуги для фильтра
$servicesStmt = executeQuery("SELECT DISTINCT service FROM contact_requests ORDER BY service");
$services = $servicesStmt ? $servicesStmt->fetchAll() : [];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_status'])) {
    $requestId = (int)$_POST['request_id'];
    $newStatus = $_POST['status'];
    
    $updateSql = "UPDATE contact_requests SET status = ? WHERE id = ?";
    executeQuery($updateSql, [$newStatus, $requestId]);
    
    header("Location: admin.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_request'])) {
    $requestId = (int)$_POST['request_id'];
    
    $deleteSql = "DELETE FROM contact_requests WHERE id = ?";
    executeQuery($deleteSql, [$requestId]);
    
    header("Location: admin.php");
    exit;
}

include __DIR__ . "/../includes/header.php";
?>

<section class="section">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h1>📊 Панель администратора</h1>
            <p>Добро пожаловать, <strong><?= htmlspecialchars(getCurrentAdminUsername()) ?></strong>!</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="admin_users.php" style="background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; font-weight: bold;">
                👥 Администраторы
            </a>
            <a href="logout.php" style="background: #ef4444; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; font-weight: bold;">
                🚪 Выход
            </a>
        </div>
    </div>
    <p>Всего заявок в базе данных: <strong><?= count($requests) ?></strong></p>

    <!-- Фильтры -->
    <div style="background: white; padding: 20px; border-radius: 10px; margin-top: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h3 style="margin-top: 0;">🔍 Фильтры</h3>
        <form method="GET" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: end;">
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Статус:</label>
                <select name="status" style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 8px;">
                    <option value="">Все статусы</option>
                    <option value="new" <?= $filterStatus === 'new' ? 'selected' : '' ?>>Новая</option>
                    <option value="in_progress" <?= $filterStatus === 'in_progress' ? 'selected' : '' ?>>В работе</option>
                    <option value="completed" <?= $filterStatus === 'completed' ? 'selected' : '' ?>>Завершена</option>
                    <option value="cancelled" <?= $filterStatus === 'cancelled' ? 'selected' : '' ?>>Отменена</option>
                </select>
            </div>

            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Услуга:</label>
                <select name="service" style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 8px;">
                    <option value="">Все услуги</option>
                    <?php foreach ($services as $srv): ?>
                        <option value="<?= htmlspecialchars($srv['service']) ?>" <?= $filterService === $srv['service'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($srv['service']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <button type="submit" style="background: #667eea; color: white; padding: 10px 25px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
                    Применить
                </button>
                <a href="admin.php" style="display: inline-block; background: #e0e0e0; color: #333; padding: 10px 25px; border-radius: 8px; text-decoration: none; font-weight: bold; margin-left: 10px;">
                    Сбросить
                </a>
            </div>
        </form>
    </div>
</section>

<?php if (empty($requests)): ?>
    <section class="section">
        <p>Пока нет ни одной заявки. Заявки будут отображаться здесь после отправки формы на странице "Контакты".</p>
    </section>
<?php else: ?>
    <section class="section">
        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Услуга</th>
                        <th>Сообщение</th>
                        <th>Дата</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $request): ?>
                        <tr>
                            <td><?= htmlspecialchars($request['id']) ?></td>
                            <td><?= htmlspecialchars($request['name']) ?></td>
                            <td><?= htmlspecialchars($request['email']) ?></td>
                            <td><?= htmlspecialchars($request['service']) ?></td>
                            <td class="message-cell">
                                <?= htmlspecialchars($request['message'] ?: '—') ?>
                            </td>
                            <td><?= date('d.m.Y H:i', strtotime($request['created_at'])) ?></td>
                            <td>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                                    <select name="status" class="status-select status-<?= $request['status'] ?>" onchange="this.form.submit()">
                                        <option value="new" <?= $request['status'] === 'new' ? 'selected' : '' ?>>Новая</option>
                                        <option value="in_progress" <?= $request['status'] === 'in_progress' ? 'selected' : '' ?>>В работе</option>
                                        <option value="completed" <?= $request['status'] === 'completed' ? 'selected' : '' ?>>Завершена</option>
                                        <option value="cancelled" <?= $request['status'] === 'cancelled' ? 'selected' : '' ?>>Отменена</option>
                                    </select>
                                    <input type="hidden" name="update_status" value="1">
                                </form>
                            </td>
                            <td>
                                <form method="post" style="display: inline;" onsubmit="return confirm('Вы уверены, что хотите удалить эту заявку?');">
                                    <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                                    <input type="hidden" name="delete_request" value="1">
                                    <button type="submit" class="btn-delete">🗑️ Удалить</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
    
    <section class="section section--muted">
        <h2>📈 Статистика</h2>
        <?php
        $stats = [
            'new' => 0,
            'in_progress' => 0,
            'completed' => 0,
            'cancelled' => 0
        ];
        foreach ($requests as $request) {
            $stats[$request['status']]++;
        }
        ?>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= $stats['new'] ?></div>
                <div class="stat-label">Новые</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['in_progress'] ?></div>
                <div class="stat-label">В работе</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['completed'] ?></div>
                <div class="stat-label">Завершены</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['cancelled'] ?></div>
                <div class="stat-label">Отменены</div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php include __DIR__ . "/../includes/footer.php"; ?>

