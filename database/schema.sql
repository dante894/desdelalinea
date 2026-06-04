CREATE TABLE IF NOT EXISTS users (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR(100),
    email         VARCHAR(150) UNIQUE,
    password      VARCHAR(255),
    role          VARCHAR(50) DEFAULT 'editor',
    active        TINYINT DEFAULT 1,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS news (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    title         VARCHAR(500) NOT NULL,
    slug          VARCHAR(500) NOT NULL UNIQUE,
    summary       TEXT,
    content       LONGTEXT,
    image_url     TEXT,
    source_url    TEXT,
    source_name   VARCHAR(100),
    category      VARCHAR(100) DEFAULT 'General',
    scraped_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    published_at  DATETIME,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_scraped_at (scraped_at)
);

INSERT IGNORE INTO users (username, email, password, role) VALUES
('admin', 'admin@desdelalinea.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
