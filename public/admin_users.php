<?php
$pageTitle = "Управление администраторами";
$activePage = "admin";

require_once __DIR__ . "/../includes/config.php";
require_once __DIR__ . "/../includes/auth.php";

requireAuth();

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['create_admin'])) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($email) || empty($password)) {
        $error = "Заполните все обязательные поля";
    } elseif (strlen($username) < 3) {
        $error = "Логин должен содержать минимум 3 символа";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Некорректный формат email";
    } elseif (strlen($password) < 6) {
        $error = "Пароль должен содержать минимум 6 символов";
    } elseif ($password !== $confirmPassword) {
        $error = "Пароли не совпадают";
    } elseif (usernameExists($username)) {
        $error = "Пользователь с таким логином уже существует";
    } else {
        if (createAdmin($username, $password, $email)) {
            $success = "Администратор успешно создан!";
        } else {
            $error = "Ошибка при создании администратора";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_admin'])) {
    $adminId = (int)$_POST['admin_id'];

    if ($adminId === getCurrentAdminId()) {
        $error = "Нельзя удалить свою учётную запись";
    } else {
        $deleteSql = "DELETE FROM admins WHERE id = ?";
        if (executeQuery($deleteSql, [$adminId])) {
            $success = "Администратор удалён";
        } else {
            $error = "Ошибка при удалении";
        }
    }
}

$adminsStmt = executeQuery("SELECT id, username, email, created_at FROM admins ORDER BY created_at DESC");
$admins = $adminsStmt ? $adminsStmt->fetchAll() : [];

include __DIR__ . "/../includes/header.php";
?>

<style>
.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.btn {
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    transition: all 0.3s;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-danger {
    background: #ef4444;
    color: white;
    border: none;
    cursor: pointer;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border-left: 4px solid #10b981;
}

.alert-error {
    background: #fee;
    color: #c33;
    border-left: 4px solid #ef4444;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 8px;
}

.form-group input {
    padding: 10px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1em;
}

.form-group input:focus {
    outline: none;
    border-color: #667eea;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.admin-table th,
.admin-table td {
    padding: 15px;
    text-align: left;
}

.admin-table th {
    background: #667eea;
    color: white;
    font-weight: bold;
}

.admin-table tr:nth-child(even) {
    background: #f9fafb;
}

.admin-table tr:hover {
    background: #f0f4ff;
}
</style>

<section class="section">
    <div class="admin-header">
        <div>
            <h1>👥 Управление администраторами</h1>
            <p>Добро пожаловать, <strong><?= htmlspecialchars(getCurrentAdminUsername()) ?></strong>!</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="admin.php" class="btn btn-primary">📊 Заявки</a>
            <a href="logout.php" class="btn btn-danger">🚪 Выход</a>
        </div>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success">
            ✅ <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-error">
            ⚠️ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <h2>➕ Создать нового администратора</h2>
    <form method="POST" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 40px;">
        <div class="form-grid">
            <div class="form-group">
                <label for="username">Логин: *</label>
                <input type="text" id="username" name="username" required minlength="3">
            </div>

            <div class="form-group">
                <label for="email">Email: *</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Пароль: *</label>
                <input type="password" id="password" name="password" required minlength="6">
            </div>

            <div class="form-group">
                <label for="confirm_password">Подтвердите пароль: *</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
            </div>
        </div>

        <button type="submit" name="create_admin" class="btn btn-primary" style="width: 100%;">
            ➕ Создать администратора
        </button>
    </form>

    <h2>📋 Список администраторов (<?= count($admins) ?>)</h2>

    <?php if (empty($admins)): ?>
        <p>Нет администраторов в системе.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Логин</th>
                    <th>Email</th>
                    <th>Дата создания</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td><?= $admin['id'] ?></td>
                        <td>
                            <strong><?= htmlspecialchars($admin['username']) ?></strong>
                            <?php if ($admin['id'] === getCurrentAdminId()): ?>
                                <span style="color: #10b981; font-size: 0.85em;">(Вы)</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($admin['email']) ?></td>
                        <td><?= date('d.m.Y H:i', strtotime($admin['created_at'])) ?></td>
                        <td>
                            <?php if ($admin['id'] !== getCurrentAdminId()): ?>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Вы уверены, что хотите удалить этого администратора?');">
                                    <input type="hidden" name="admin_id" value="<?= $admin['id'] ?>">
                                    <button type="submit" name="delete_admin" class="btn btn-danger" style="padding: 5px 15px; font-size: 0.9em;">
                                        🗑️ Удалить
                                    </button>
                                </form>
                            <?php else: ?>
                                <span style="color: #999; font-size: 0.9em;">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<?php include __DIR__ . "/../includes/footer.php"; ?>

