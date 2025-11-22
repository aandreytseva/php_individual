<?php
$pageTitle = "О нас";
$activePage = "about";

require_once __DIR__ . "/../includes/config.php";

// Получаем отзывы
$sql = "SELECT * FROM testimonials WHERE is_published = 1 ORDER BY created_at DESC LIMIT 6";
$stmt = executeQuery($sql);
$testimonials = $stmt ? $stmt->fetchAll() : [];

include __DIR__ . "/../includes/header.php";
?>

<style>
    .about-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 80px 20px;
        text-align: center;
        margin-bottom: 60px;
    }

    .about-hero h1 {
        font-size: 3em;
        margin-bottom: 20px;
        animation: fadeInDown 1s;
    }

    .about-hero p {
        font-size: 1.3em;
        max-width: 800px;
        margin: 0 auto;
        opacity: 0.95;
    }

    .stats-section {
        background: #f8f9fa;
        padding: 60px 20px;
        margin-bottom: 60px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .stat-card {
        text-align: center;
        padding: 30px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-10px);
    }

    .stat-number {
        font-size: 3em;
        font-weight: bold;
        color: #667eea;
        margin-bottom: 10px;
    }

    .stat-label {
        color: #666;
        font-size: 1.1em;
    }

    .team-section {
        max-width: 1200px;
        margin: 0 auto 60px;
        padding: 0 20px;
    }

    .team-section h2 {
        text-align: center;
        font-size: 2.5em;
        margin-bottom: 50px;
        color: #333;
    }

    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 40px;
    }

    .team-member {
        text-align: center;
        padding: 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s;
    }

    .team-member:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }

    .team-member img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 20px;
        border: 5px solid #667eea;
    }

    .team-member h3 {
        color: #333;
        margin-bottom: 5px;
    }

    .team-member .role {
        color: #667eea;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .team-member p {
        color: #666;
        font-size: 0.95em;
        line-height: 1.6;
    }

    .values-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 60px 20px;
        margin-bottom: 60px;
    }

    .values-section h2 {
        text-align: center;
        font-size: 2.5em;
        margin-bottom: 50px;
    }

    .values-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .value-card {
        background: rgba(255,255,255,0.1);
        padding: 30px;
        border-radius: 12px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
    }

    .value-card .icon {
        font-size: 3em;
        margin-bottom: 15px;
    }

    .value-card h3 {
        font-size: 1.5em;
        margin-bottom: 15px;
    }

    .value-card p {
        opacity: 0.9;
        line-height: 1.6;
    }

    .timeline-section {
        max-width: 900px;
        margin: 0 auto 60px;
        padding: 0 20px;
    }

    .timeline-section h2 {
        text-align: center;
        font-size: 2.5em;
        margin-bottom: 50px;
        color: #333;
    }

    .timeline {
        position: relative;
        padding-left: 50px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 3px;
        background: #667eea;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 40px;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -38px;
        top: 25px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #667eea;
        border: 3px solid white;
        box-shadow: 0 0 0 3px #667eea;
    }

    .timeline-year {
        color: #667eea;
        font-weight: bold;
        font-size: 1.3em;
        margin-bottom: 10px;
    }

    .timeline-item h3 {
        color: #333;
        margin-bottom: 10px;
    }

    .timeline-item p {
        color: #666;
        line-height: 1.6;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .testimonials-section {
        background: #f8f9fa;
        padding: 60px 20px;
        margin-bottom: 0;
    }

    .testimonials-section h2 {
        text-align: center;
        font-size: 2.5em;
        margin-bottom: 50px;
        color: #333;
    }

    .testimonials-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .testimonial-card {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s;
    }

    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }

    .testimonial-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .testimonial-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 15px;
    }

    .testimonial-info h4 {
        margin: 0 0 5px 0;
        color: #333;
    }

    .testimonial-info p {
        margin: 0;
        font-size: 0.9em;
        color: #666;
    }

    .testimonial-rating {
        color: #fbbf24;
        font-size: 1.2em;
        margin-bottom: 15px;
    }

    .testimonial-text {
        color: #555;
        line-height: 1.6;
        font-style: italic;
    }
</style>

<div class="about-hero">
    <h1>О компании PromoService</h1>
    <p>Мы создаём цифровые решения, которые помогают бизнесу расти. Наша команда профессионалов превращает идеи в успешные проекты с измеримым результатом.</p>
</div>

<div class="stats-section">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">150+</div>
            <div class="stat-label">Завершённых проектов</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">98%</div>
            <div class="stat-label">Довольных клиентов</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">7</div>
            <div class="stat-label">Лет на рынке</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">24/7</div>
            <div class="stat-label">Поддержка клиентов</div>
        </div>
    </div>
</div>

<div class="team-section">
    <h2>👥 Наша команда</h2>
    <div class="team-grid">
        <div class="team-member">
            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Алексей Петров">
            <h3>Алексей Петров</h3>
            <div class="role">CEO & Основатель</div>
            <p>15 лет опыта в digital-маркетинге. Создал более 200 успешных проектов для бизнеса.</p>
        </div>

        <div class="team-member">
            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Мария Иванова">
            <h3>Мария Иванова</h3>
            <div class="role">Главный дизайнер</div>
            <p>Эксперт в UX/UI дизайне. Создаёт интерфейсы, которые любят пользователи.</p>
        </div>


        <div class="team-member">
            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Елена Смирнова">
            <h3>Елена Смирнова</h3>
            <div class="role">Менеджер проектов</div>
            <p>Организует работу команды и следит за сроками. Гарантирует качество каждого проекта.</p>
        </div>
    </div>
</div>

<div class="values-section">
    <h2>Наши ценности</h2>
    <div class="values-grid">
        <div class="value-card">
            <h3>Результат</h3>
            <p>Мы фокусируемся на достижении конкретных бизнес-целей клиента, а не просто на выполнении задач.</p>
        </div>

        <div class="value-card">
            <h3>Партнёрство</h3>
            <p>Мы не просто подрядчики — мы партнёры, которые заинтересованы в успехе вашего бизнеса.</p>
        </div>

        <div class="value-card">
            <h3>Инновации</h3>
            <p>Используем современные технологии и подходы для создания конкурентных преимуществ.</p>
        </div>

        <div class="value-card">
            <h3>Качество</h3>
            <p>Каждый проект проходит тщательное тестирование и проверку перед запуском.</p>
        </div>
    </div>
</div>

<div class="timeline-section">
    <h2>Наша история</h2>
    <div class="timeline">
        <div class="timeline-item">
            <div class="timeline-year">2018</div>
            <h3>Основание компании</h3>
            <p>Начали с небольшой команды из 3 человек и первых 5 клиентов. Фокус на качестве, а не количестве.</p>
        </div>

        <div class="timeline-item">
            <div class="timeline-year">2019</div>
            <h3>Первые 50 проектов</h3>
            <p>Расширили команду до 8 специалистов. Запустили отдел веб-разработки и дизайна.</p>
        </div>

        <div class="timeline-item">
            <div class="timeline-year">2021</div>
            <h3>Выход на новый уровень</h3>
            <p>Открыли офис в центре города. Начали работать с крупными корпоративными клиентами.</p>
        </div>

        <div class="timeline-item">
            <div class="timeline-year">2023</div>
            <h3>100+ успешных проектов</h3>
            <p>Достигли отметки в 100 завершённых проектов. Получили награду "Лучшее digital-агентство года".</p>
        </div>

        <div class="timeline-item">
            <div class="timeline-year">2025</div>
            <h3>Сегодня</h3>
            <p>Команда из 15+ профессионалов. Работаем с клиентами по всей России. Продолжаем расти и развиваться.</p>
        </div>
    </div>
</div>

<!-- Отзывы клиентов -->
<div class="testimonials-section">
    <h2>⭐ Отзывы наших клиентов</h2>

    <?php if (empty($testimonials)): ?>
        <p style="text-align: center; color: #666;">Отзывы пока не добавлены.</p>
    <?php else: ?>
        <div class="testimonials-grid">
            <?php foreach ($testimonials as $testimonial): ?>
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <img src="<?= htmlspecialchars($testimonial['avatar_url']) ?>"
                             alt="<?= htmlspecialchars($testimonial['client_name']) ?>"
                             class="testimonial-avatar">
                        <div class="testimonial-info">
                            <h4><?= htmlspecialchars($testimonial['client_name']) ?></h4>
                            <p><?= htmlspecialchars($testimonial['position']) ?></p>
                            <p><strong><?= htmlspecialchars($testimonial['company']) ?></strong></p>
                        </div>
                    </div>

                    <div class="testimonial-rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span><?= $i <= $testimonial['rating'] ? '★' : '☆' ?></span>
                        <?php endfor; ?>
                    </div>

                    <p class="testimonial-text">
                        "<?= htmlspecialchars($testimonial['testimonial']) ?>"
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>

