CREATE DATABASE IF NOT EXISTS agromarket_admin
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE agromarket_admin;

CREATE TABLE IF NOT EXISTS admins (
    id CHAR(32) NOT NULL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'admin', 'editor') NOT NULL DEFAULT 'admin',
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login DATETIME NULL,
    INDEX idx_admin_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS leads (
    id CHAR(32) NOT NULL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NULL,
    subject VARCHAR(200) NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'contacted', 'converted', 'lost') NOT NULL DEFAULT 'new',
    notes TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_leads_status_created (status, created_at),
    INDEX idx_leads_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS blog_categories (
    id CHAR(32) NOT NULL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS blog_tags (
    id CHAR(32) NOT NULL PRIMARY KEY,
    name VARCHAR(80) NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS blogs (
    id CHAR(32) NOT NULL PRIMARY KEY,
    category_id CHAR(32) NULL,
    tag VARCHAR(80) NOT NULL DEFAULT 'General',
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    short_description VARCHAR(500) NULL,
    content MEDIUMTEXT NOT NULL,
    featured_image VARCHAR(255) NULL,
    author VARCHAR(100) NULL,
    status ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'draft',
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    seo_title VARCHAR(255) NULL,
    seo_description TEXT NULL,
    seo_keywords VARCHAR(500) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    published_at DATETIME NULL,
    INDEX idx_blogs_category (category_id),
    INDEX idx_blogs_status_created (status, created_at),
    CONSTRAINT fk_blogs_category
        FOREIGN KEY (category_id) REFERENCES blog_categories(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS category_map (
    id CHAR(32) NOT NULL PRIMARY KEY,
    blog_id CHAR(32) NOT NULL,
    category_id CHAR(32) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_category_map_pair (blog_id, category_id),
    INDEX idx_category_map_category (category_id),
    CONSTRAINT fk_category_map_blog
        FOREIGN KEY (blog_id) REFERENCES blogs(id) ON DELETE CASCADE,
    CONSTRAINT fk_category_map_category
        FOREIGN KEY (category_id) REFERENCES blog_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS tag_map (
    id CHAR(32) NOT NULL PRIMARY KEY,
    blog_id CHAR(32) NOT NULL,
    tag_id CHAR(32) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_tag_map_pair (blog_id, tag_id),
    INDEX idx_tag_map_tag (tag_id),
    CONSTRAINT fk_tag_map_blog
        FOREIGN KEY (blog_id) REFERENCES blogs(id) ON DELETE CASCADE,
    CONSTRAINT fk_tag_map_tag
        FOREIGN KEY (tag_id) REFERENCES blog_tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO blog_tags (id, name, slug, created_at, updated_at)
SELECT LOWER(REPLACE(UUID(), '-', '')), legacy.name,
       CONCAT('legacy-', LOWER(REPLACE(UUID(), '-', ''))), UTC_TIMESTAMP(), UTC_TIMESTAMP()
FROM (
    SELECT DISTINCT TRIM(tag) AS name
    FROM blogs
    WHERE TRIM(COALESCE(tag, '')) <> ''
) AS legacy;

INSERT IGNORE INTO category_map (blog_id, category_id)
SELECT id, category_id
FROM blogs
WHERE category_id IS NOT NULL;

INSERT IGNORE INTO tag_map (blog_id, tag_id)
SELECT b.id, t.id
FROM blogs b
INNER JOIN blog_tags t ON t.name = TRIM(b.tag)
WHERE TRIM(COALESCE(b.tag, '')) <> '';