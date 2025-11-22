<?php
$pageTitle = "Вход в систему";
$activePage = "login";

require_once __DIR__ . "/../includes/config.php";
require_once __DIR__ . "/../includes/auth.php";

if (isLoggedIn()) {
    header("Location: admin.php");
    exit;
}

$username = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    
    if (empty($username) || empty($password)) {
        $error = "Заполните все поля";
    } else {
        $admin = verifyAdminCredentials($username, $password);
        
        if ($admin) {
            loginAdmin($admin['id'], $admin['username']);
            
            header("Location: admin.php");
            exit;
        } else {
            $error = "Неверный логин или пароль";
        }
    }
}

include __DIR__ . "/../includes/header.php";
?>

<style>
.login-container {
    max-width: 450px;
    margin: 80px auto;
    padding: 40px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}

.login-header {
    text-align: center;
    margin-bottom: 30px;
}

.login-header h1 {
    font-size: 2em;
    margin-bottom: 10px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.login-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
}

.form-group input {
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1em;
    transition: all 0.3s;
}

.form-group input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.error-message {
    background: #fee;
    color: #c33;
    padding: 12px 15px;
    border-radius: 8px;
    border-left: 4px solid #c33;
    font-size: 0.95em;
}

.login-button {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 14px;
    border: none;
    border-radius: 8px;
    font-size: 1.1em;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
}

.login-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
}

.login-info {
    margin-top: 20px;
    padding: 15px;
    background: #f0f4ff;
    border-radius: 8px;
    font-size: 0.9em;
    color: #555;
}
</style>

<div class="login-container">
    <div class="login-header">
        <h1>🔐 Вход в систему</h1>
        <p>Панель администратора</p>
    </div>
    
    <?php if ($error): ?>
        <div class="error-message">
            ⚠️ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" class="login-form">
        <div class="form-group">
            <label for="username">Логин:</label>
            <input 
                type="text" 
                id="username" 
                name="username" 
                value="<?= htmlspecialchars($username) ?>"
                required
                autocomplete="username"
            >
        </div>
        
        <div class="form-group">
            <label for="password">Пароль:</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                required
                autocomplete="current-password"
            >
        </div>
        
        <button type="submit" class="login-button">
            Войти →
        </button>
    </form>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>

