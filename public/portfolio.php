<?php
$pageTitle = "Портфолио - Наши работы";
$activePage = "portfolio";

require_once __DIR__ . "/../includes/config.php";

$category = $_GET['category'] ?? 'all';
$searchQuery = trim($_GET['q'] ?? '');

// Формируем SQL с учётом поиска и категории
$sql = "SELECT * FROM portfolio WHERE is_published = 1";
$params = [];

if (!empty($searchQuery)) {
    $sql .= " AND (title LIKE ? OR description LIKE ? OR client_name LIKE ?)";
    $searchParam = "%{$searchQuery}%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
}

if ($category !== 'all') {
    $sql .= " AND category = ?";
    $params[] = $category;
}

$sql .= " ORDER BY completion_date DESC";

$stmt = executeQuery($sql, $params);
$projects = $stmt ? $stmt->fetchAll() : [];

$categoriesSql = "SELECT DISTINCT category FROM portfolio WHERE is_published = 1 ORDER BY category";
$categoriesStmt = executeQuery($categoriesSql);
$categories = $categoriesStmt ? $categoriesStmt->fetchAll(PDO::FETCH_COLUMN) : [];

include __DIR__ . "/../includes/header.php";
?>

<section class="section">
    <h1>🎨 Наши работы</h1>
    <p>Более 50 успешных проектов в различных сферах бизнеса</p>

    <!-- Поиск -->
    <form method="GET" style="margin-top: 30px; max-width: 600px;">
        <div style="display: flex; gap: 10px;">
            <input
                type="text"
                name="q"
                placeholder="🔍 Поиск по названию, описанию или клиенту..."
                value="<?= htmlspecialchars($searchQuery) ?>"
                style="flex: 1; padding: 12px 20px; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 1em;"
            >
            <button type="submit" style="background: #667eea; color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: bold; cursor: pointer;">
                Найти
            </button>
            <?php if (!empty($searchQuery) || $category !== 'all'): ?>
                <a href="portfolio.php" style="background: #e0e0e0; color: #333; padding: 12px 25px; border-radius: 10px; text-decoration: none; font-weight: bold; display: flex; align-items: center;">
                    Сбросить
                </a>
            <?php endif; ?>
        </div>
        <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
    </form>

    <?php if (!empty($searchQuery)): ?>
        <p style="margin-top: 20px; color: #667eea; font-weight: bold;">
            Результаты поиска: "<?= htmlspecialchars($searchQuery) ?>" — найдено проектов: <?= count($projects) ?>
        </p>
    <?php endif; ?>
</section>

<section class="section">
    <div class="portfolio-filters">
        <a href="portfolio.php?category=all<?= !empty($searchQuery) ? '&q=' . urlencode($searchQuery) : '' ?>"
           class="filter-btn <?= $category === 'all' ? 'active' : '' ?>">
            Все проекты
        </a>
        <?php foreach ($categories as $cat): ?>
            <a href="portfolio.php?category=<?= urlencode($cat) ?><?= !empty($searchQuery) ? '&q=' . urlencode($searchQuery) : '' ?>"
               class="filter-btn <?= $category === $cat ? 'active' : '' ?>">
                <?= htmlspecialchars($cat) ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<?php if (empty($projects)): ?>
    <section class="section">
        <p>Проекты в этой категории пока не добавлены.</p>
    </section>
<?php else: ?>
    <section class="section">
        <div class="portfolio-grid">
            <?php foreach ($projects as $project): ?>
                <div class="portfolio-card">
                    <div class="portfolio-card__image">
                        <img src="<?= htmlspecialchars($project['image_url']) ?>" 
                             alt="<?= htmlspecialchars($project['title']) ?>">
                        <div class="portfolio-card__overlay">
                            <span class="portfolio-card__category">
                                <?= htmlspecialchars($project['category']) ?>
                            </span>
                        </div>
                    </div>
                    <div class="portfolio-card__content">
                        <h3><?= htmlspecialchars($project['title']) ?></h3>
                        <p class="portfolio-card__client">
                            <strong>Клиент:</strong> <?= htmlspecialchars($project['client_name']) ?>
                        </p>
                        <p class="portfolio-card__description">
                            <?= htmlspecialchars($project['description']) ?>
                        </p>
                        <div class="portfolio-card__footer">
                            <span class="portfolio-card__date">
                                📅 <?= date('d.m.Y', strtotime($project['completion_date'])) ?>
                            </span>
                            <?php if ($project['project_url']): ?>
                                <a href="<?= htmlspecialchars($project['project_url']) ?>" 
                                   target="_blank" 
                                   class="portfolio-card__link">
                                    Посмотреть проект →
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<section class="section section--muted">
    <div class="cta-box">
        <h2>Хотите такой же результат?</h2>
        <p>Оставьте заявку, и мы разработаем стратегию продвижения специально для вашего бизнеса</p>
        <a href="contact.php" class="btn">Получить консультацию</a>
    </div>
</section>

<?php include __DIR__ . "/../includes/footer.php"; ?>

