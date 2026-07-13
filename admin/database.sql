-- AMPL Agro Market Admin Database Setup
-- Run this SQL to create all necessary tables

-- Create database if not exists
-- CREATE DATABASE IF NOT EXISTS agromarket;
-- USE agromarket;

-- Admins table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'admin', 'editor') DEFAULT 'admin',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login DATETIME NULL,
    INDEX idx_email (email),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Leads table (Contact form submissions)
CREATE TABLE IF NOT EXISTS leads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NULL,
    subject VARCHAR(200) NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'contacted', 'converted', 'lost') DEFAULT 'new',
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_email (email),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Blog Categories table
CREATE TABLE IF NOT EXISTS blog_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Blog Posts table
CREATE TABLE IF NOT EXISTS blogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NULL,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    short_description VARCHAR(500) NULL,
    content TEXT NOT NULL,
    featured_image VARCHAR(255) NULL,
    author VARCHAR(100) NULL,
    tags VARCHAR(255) NULL,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    view_count INT DEFAULT 0,
    is_featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    published_at DATETIME NULL,
    FOREIGN KEY (category_id) REFERENCES blog_categories(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    INDEX idx_category (category_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products table (for future use)
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    category VARCHAR(100) NULL,
    short_description VARCHAR(500) NULL,
    description TEXT NULL,
    price DECIMAL(10,2) NULL,
    featured_image VARCHAR(255) NULL,
    gallery_images TEXT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    is_featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: admin123)
-- Password hash: password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO admins (name, email, password, role, status) VALUES 
('Super Admin', 'admin@agromarket.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', 'active')
ON DUPLICATE KEY UPDATE name = name;

-- Insert sample blog categories
INSERT INTO blog_categories (name, slug, description) VALUES 
('Organic Farming', 'organic-farming', 'Articles about organic farming practices'),
('Agriculture Tips', 'agriculture-tips', 'Helpful tips for farmers and growers'),
('Market News', 'market-news', 'Latest news from agricultural markets'),
('Sustainability', 'sustainability', 'Sustainable farming and environmental practices')
ON DUPLICATE KEY UPDATE name = name;

-- Insert sample blog posts
INSERT INTO blogs (category_id, title, slug, short_description, content, author, tags, status, published_at) VALUES 
(1, 'Benefits of Organic Farming', 'benefits-of-organic-farming', 'Discover the many benefits of organic farming for your health and the environment.', '<p>Organic farming is becoming increasingly popular as people become more conscious of what they eat and how it affects the environment.</p><p>At AMPL Agro Market, we believe in the power of organic agriculture...</p>', 'Admin', 'organic,farming,health', 'published', NOW()),
(2, 'Best Practices for Crop Rotation', 'best-practices-crop-rotation', 'Learn about the importance of crop rotation and how to implement it in your farm.', '<p>Crop rotation is a fundamental practice in sustainable agriculture...</p>', 'Admin', 'crops,farming,tips', 'published', NOW()),
(3, 'Understanding Market Trends', 'understanding-market-trends', 'Stay updated with the latest trends in agricultural markets.', '<p>The agricultural market is constantly evolving...</p>', 'Admin', 'market,trends,business', 'published', NOW())
ON DUPLICATE KEY UPDATE title = title;