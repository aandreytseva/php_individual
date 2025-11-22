
CREATE DATABASE IF NOT EXISTS service_promo_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE service_promo_db;

CREATE TABLE IF NOT EXISTS contact_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    service VARCHAR(255) NOT NULL,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('new', 'in_progress', 'completed', 'cancelled') DEFAULT 'new',
    INDEX idx_email (email),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO admins (username, password_hash, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com');

INSERT INTO contact_requests (name, email, service, message, status) VALUES
('Иван Петров', 'ivan@example.com', 'Продвижение сайта', 'Хочу продвинуть свой интернет-магазин', 'new'),
('Мария Сидорова', 'maria@example.com', 'Контекстная реклама', 'Интересует настройка Яндекс.Директ', 'in_progress'),
('Алексей Смирнов', 'alex@example.com', 'SEO оптимизация', 'Нужна помощь с SEO для корпоративного сайта', 'new');

CREATE TABLE IF NOT EXISTS portfolio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    client_name VARCHAR(255),
    category VARCHAR(100),
    image_url VARCHAR(500),
    project_url VARCHAR(500),
    completion_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_published BOOLEAN DEFAULT TRUE,
    INDEX idx_category (category),
    INDEX idx_published (is_published)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO portfolio (title, description, client_name, category, image_url, project_url, completion_date) VALUES
('Интернет-магазин электроники TechStore', 'Разработка и продвижение интернет-магазина. Увеличение продаж на 250% за 6 месяцев. Настройка контекстной рекламы и SEO-оптимизация.', 'ООО "ТехСтор"', 'E-commerce', 'https://via.placeholder.com/600x400/4F46E5/ffffff?text=TechStore', 'https://example.com', '2024-10-15'),
('Корпоративный сайт юридической компании', 'Создание современного корпоративного сайта с личным кабинетом клиентов. Интеграция с CRM-системой.', 'Юридическая группа "Правовед"', 'Корпоративный сайт', 'https://via.placeholder.com/600x400/10B981/ffffff?text=Law+Firm', 'https://example.com', '2024-09-20'),
('Продвижение медицинской клиники', 'Комплексное продвижение: SEO, контекстная реклама, SMM. Рост обращений на 180% за 4 месяца.', 'Медицинский центр "Здоровье+"', 'Медицина', 'https://via.placeholder.com/600x400/EF4444/ffffff?text=Medical+Center', 'https://example.com', '2024-11-01'),
('Лендинг для образовательных курсов', 'Разработка продающего лендинга с интеграцией платежной системы. Конверсия 12%.', 'Онлайн-школа "Skillbox Pro"', 'Образование', 'https://via.placeholder.com/600x400/F59E0B/ffffff?text=Online+Courses', 'https://example.com', '2024-08-10'),
('Ресторан доставки еды FoodExpress', 'Создание сайта с онлайн-заказом, интеграция с системой доставки. Увеличение заказов на 300%.', 'FoodExpress', 'Ресторан', 'https://via.placeholder.com/600x400/8B5CF6/ffffff?text=Food+Delivery', 'https://example.com', '2024-07-25'),
('Фитнес-клуб "Атлетика"', 'Разработка сайта с онлайн-записью на тренировки, личным кабинетом и мобильным приложением.', 'Фитнес-клуб "Атлетика"', 'Спорт', 'https://via.placeholder.com/600x400/06B6D4/ffffff?text=Fitness+Club', 'https://example.com', '2024-06-30');

CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(255) NOT NULL,
    company VARCHAR(255),
    position VARCHAR(255),
    testimonial TEXT NOT NULL,
    rating INT DEFAULT 5,
    avatar_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_published BOOLEAN DEFAULT TRUE,
    INDEX idx_rating (rating),
    INDEX idx_published (is_published)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO testimonials (client_name, company, position, testimonial, rating, avatar_url) VALUES
('Дмитрий Соколов', 'ООО "ТехСтор"', 'Генеральный директор', 'Отличная работа! Наш интернет-магазин вышел в топ-3 по ключевым запросам. Продажи выросли в 2.5 раза. Команда профессионалов, всегда на связи и оперативно решают любые вопросы.', 5, 'https://i.pravatar.cc/150?img=12'),
('Елена Морозова', 'Юридическая группа "Правовед"', 'Управляющий партнер', 'Очень довольны результатом! Сайт получился современным и функциональным. Особенно понравилась интеграция с CRM - теперь работа с клиентами стала намного эффективнее.', 5, 'https://i.pravatar.cc/150?img=5'),
('Андрей Волков', 'Медицинский центр "Здоровье+"', 'Директор по маркетингу', 'Сотрудничаем уже полгода. Количество записей через сайт увеличилось почти в 2 раза. Реклама настроена грамотно, бюджет расходуется эффективно. Рекомендую!', 5, 'https://i.pravatar.cc/150?img=33'),
('Ольга Петрова', 'Онлайн-школа "Skillbox Pro"', 'Руководитель отдела продаж', 'Лендинг превзошел все ожидания! Конверсия 12% - это отличный результат для нашей ниши. Дизайн современный, все работает быстро и без сбоев.', 5, 'https://i.pravatar.cc/150?img=9'),
('Максим Кузнецов', 'FoodExpress', 'Владелец', 'Заказов стало в 3 раза больше после запуска нового сайта! Система онлайн-заказов работает безупречно. Спасибо за профессионализм!', 5, 'https://i.pravatar.cc/150?img=15'),
('Светлана Иванова', 'Фитнес-клуб "Атлетика"', 'Директор', 'Отличный сайт и мобильное приложение! Клиенты в восторге от удобства онлайн-записи. Посещаемость клуба выросла на 40%. Очень благодарны за работу!', 5, 'https://i.pravatar.cc/150?img=20');

