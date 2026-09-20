-- Gita in Daily Life — full schema (Phase 1)
-- Safe to re-run: drops and recreates the database.

CREATE DATABASE IF NOT EXISTS gita_in_daily_life CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gita_in_daily_life;

SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------- identity

DROP TABLE IF EXISTS role_permissions;
DROP TABLE IF EXISTS permissions;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS users;

CREATE TABLE roles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description VARCHAR(255) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE permissions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE role_permissions (
    role_id INT UNSIGNED NOT NULL,
    permission_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    username VARCHAR(60) NOT NULL UNIQUE,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role_id INT UNSIGNED NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    failed_login_attempts INT UNSIGNED NOT NULL DEFAULT 0,
    locked_until DATETIME NULL,
    last_login_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL,
    INDEX idx_users_status (status)
) ENGINE=InnoDB;

-- ------------------------------------------------------------------ gitas

DROP TABLE IF EXISTS gita_translations;
DROP TABLE IF EXISTS gitas;

CREATE TABLE gitas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(160) NOT NULL UNIQUE,
    alternate_names VARCHAR(255) NULL,
    short_description VARCHAR(500) NULL,
    introduction TEXT NULL,
    historical_context TEXT NULL,
    philosophy TEXT NULL,
    main_themes TEXT NULL,
    author_attribution VARCHAR(255) NULL,
    cover_image VARCHAR(255) NULL,
    language_available VARCHAR(100) NULL DEFAULT 'en',
    featured TINYINT(1) NOT NULL DEFAULT 0,
    status ENUM('draft','pending','published','archived') NOT NULL DEFAULT 'draft',
    meta_title VARCHAR(255) NULL,
    meta_description VARCHAR(500) NULL,
    created_by INT UNSIGNED NULL,
    updated_by INT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_gitas_status (status),
    INDEX idx_gitas_featured (featured)
) ENGINE=InnoDB;

CREATE TABLE gita_translations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    gita_id INT UNSIGNED NOT NULL,
    language_code VARCHAR(5) NOT NULL,
    name VARCHAR(150) NULL,
    short_description VARCHAR(500) NULL,
    introduction TEXT NULL,
    UNIQUE KEY uniq_gita_lang (gita_id, language_code),
    FOREIGN KEY (gita_id) REFERENCES gitas(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- --------------------------------------------------------------- chapters

DROP TABLE IF EXISTS chapter_translations;
DROP TABLE IF EXISTS chapters;

CREATE TABLE chapters (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    gita_id INT UNSIGNED NOT NULL,
    chapter_number INT UNSIGNED NOT NULL,
    name VARCHAR(200) NOT NULL,
    sanskrit_name VARCHAR(200) NULL,
    translation_name VARCHAR(200) NULL,
    summary TEXT NULL,
    main_ideas TEXT NULL,
    status ENUM('draft','pending','published','archived') NOT NULL DEFAULT 'draft',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_gita_chapter (gita_id, chapter_number),
    FOREIGN KEY (gita_id) REFERENCES gitas(id) ON DELETE CASCADE,
    INDEX idx_chapters_status (status)
) ENGINE=InnoDB;

CREATE TABLE chapter_translations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    chapter_id INT UNSIGNED NOT NULL,
    language_code VARCHAR(5) NOT NULL,
    name VARCHAR(200) NULL,
    summary TEXT NULL,
    UNIQUE KEY uniq_chapter_lang (chapter_id, language_code),
    FOREIGN KEY (chapter_id) REFERENCES chapters(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------------------------------------- verses

DROP TABLE IF EXISTS verse_sources;
DROP TABLE IF EXISTS verse_translations;
DROP TABLE IF EXISTS verses;

CREATE TABLE verses (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    gita_id INT UNSIGNED NOT NULL,
    chapter_id INT UNSIGNED NOT NULL,
    verse_number INT UNSIGNED NOT NULL,
    sanskrit_text TEXT NULL,
    transliteration TEXT NULL,
    word_by_word_meaning TEXT NULL,
    literal_translation TEXT NULL,
    simple_translation_en TEXT NULL,
    translation_bn TEXT NULL,
    explanation TEXT NULL,
    philosophical_meaning TEXT NULL,
    key_teaching VARCHAR(500) NULL,
    audio_path VARCHAR(255) NULL,
    video_path VARCHAR(255) NULL,
    translator VARCHAR(150) NULL,
    commentator VARCHAR(150) NULL,
    featured TINYINT(1) NOT NULL DEFAULT 0,
    status ENUM('draft','pending','published','archived') NOT NULL DEFAULT 'draft',
    created_by INT UNSIGNED NULL,
    updated_by INT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_chapter_verse (chapter_id, verse_number),
    FOREIGN KEY (gita_id) REFERENCES gitas(id) ON DELETE CASCADE,
    FOREIGN KEY (chapter_id) REFERENCES chapters(id) ON DELETE CASCADE,
    INDEX idx_verses_status (status),
    INDEX idx_verses_featured (featured)
) ENGINE=InnoDB;

CREATE TABLE verse_translations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    verse_id INT UNSIGNED NOT NULL,
    language_code VARCHAR(5) NOT NULL,
    translation_text TEXT NULL,
    explanation TEXT NULL,
    UNIQUE KEY uniq_verse_lang (verse_id, language_code),
    FOREIGN KEY (verse_id) REFERENCES verses(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE verse_sources (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    verse_id INT UNSIGNED NOT NULL,
    source_title VARCHAR(255) NULL,
    author VARCHAR(150) NULL,
    translator VARCHAR(150) NULL,
    publisher VARCHAR(150) NULL,
    edition VARCHAR(100) NULL,
    publication_year VARCHAR(10) NULL,
    url VARCHAR(255) NULL,
    notes TEXT NULL,
    FOREIGN KEY (verse_id) REFERENCES verses(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------------------------------- topics/tags

DROP TABLE IF EXISTS teaching_tags;
DROP TABLE IF EXISTS verse_tags;
DROP TABLE IF EXISTS tags;
DROP TABLE IF EXISTS topics;

CREATE TABLE topics (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(160) NOT NULL UNIQUE,
    description TEXT NULL,
    status ENUM('draft','pending','published','archived') NOT NULL DEFAULT 'published',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_topics_status (status)
) ENGINE=InnoDB;

CREATE TABLE tags (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE verse_tags (
    verse_id INT UNSIGNED NOT NULL,
    tag_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (verse_id, tag_id),
    FOREIGN KEY (verse_id) REFERENCES verses(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -------------------------------------------------------------- teachings

DROP TABLE IF EXISTS verse_teachings;
DROP TABLE IF EXISTS teaching_topics;
DROP TABLE IF EXISTS teaching_translations;
DROP TABLE IF EXISTS teachings;

CREATE TABLE teachings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(280) NOT NULL UNIQUE,
    life_problem TEXT NULL,
    relevant_teaching TEXT NULL,
    what_it_says TEXT NULL,
    what_it_does_not_mean TEXT NULL,
    how_to_apply TEXT NULL,
    reflection_question VARCHAR(500) NULL,
    practice TEXT NULL,
    status ENUM('draft','pending','published','archived') NOT NULL DEFAULT 'draft',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_teachings_status (status)
) ENGINE=InnoDB;

CREATE TABLE teaching_translations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    teaching_id INT UNSIGNED NOT NULL,
    language_code VARCHAR(5) NOT NULL,
    title VARCHAR(255) NULL,
    relevant_teaching TEXT NULL,
    how_to_apply TEXT NULL,
    UNIQUE KEY uniq_teaching_lang (teaching_id, language_code),
    FOREIGN KEY (teaching_id) REFERENCES teachings(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE teaching_tags (
    teaching_id INT UNSIGNED NOT NULL,
    tag_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (teaching_id, tag_id),
    FOREIGN KEY (teaching_id) REFERENCES teachings(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE teaching_topics (
    teaching_id INT UNSIGNED NOT NULL,
    topic_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (teaching_id, topic_id),
    FOREIGN KEY (teaching_id) REFERENCES teachings(id) ON DELETE CASCADE,
    FOREIGN KEY (topic_id) REFERENCES topics(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE verse_topics (
    verse_id INT UNSIGNED NOT NULL,
    topic_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (verse_id, topic_id),
    FOREIGN KEY (verse_id) REFERENCES verses(id) ON DELETE CASCADE,
    FOREIGN KEY (topic_id) REFERENCES topics(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE verse_teachings (
    verse_id INT UNSIGNED NOT NULL,
    teaching_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (verse_id, teaching_id),
    FOREIGN KEY (verse_id) REFERENCES verses(id) ON DELETE CASCADE,
    FOREIGN KEY (teaching_id) REFERENCES teachings(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------- situations

DROP TABLE IF EXISTS mantra_situations;
DROP TABLE IF EXISTS teaching_situations;
DROP TABLE IF EXISTS verse_situations;
DROP TABLE IF EXISTS situations;

CREATE TABLE situations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(160) NOT NULL UNIQUE,
    description TEXT NULL,
    icon VARCHAR(60) NULL,
    status ENUM('draft','pending','published','archived') NOT NULL DEFAULT 'published',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_situations_status (status)
) ENGINE=InnoDB;

CREATE TABLE verse_situations (
    verse_id INT UNSIGNED NOT NULL,
    situation_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (verse_id, situation_id),
    FOREIGN KEY (verse_id) REFERENCES verses(id) ON DELETE CASCADE,
    FOREIGN KEY (situation_id) REFERENCES situations(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE teaching_situations (
    teaching_id INT UNSIGNED NOT NULL,
    situation_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (teaching_id, situation_id),
    FOREIGN KEY (teaching_id) REFERENCES teachings(id) ON DELETE CASCADE,
    FOREIGN KEY (situation_id) REFERENCES situations(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------- mantras

DROP TABLE IF EXISTS mantra_gitas;
DROP TABLE IF EXISTS mantra_teachings;
DROP TABLE IF EXISTS mantras;

CREATE TABLE mantras (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(280) NOT NULL UNIQUE,
    sanskrit TEXT NULL,
    transliteration TEXT NULL,
    pronunciation_bn TEXT NULL,
    pronunciation_en TEXT NULL,
    meaning TEXT NULL,
    translation TEXT NULL,
    deity_association VARCHAR(150) NULL,
    purpose VARCHAR(500) NULL,
    traditionally_recited VARCHAR(255) NULL,
    suggested_duration VARCHAR(100) NULL,
    audio_path VARCHAR(255) NULL,
    video_path VARCHAR(255) NULL,
    source VARCHAR(255) NULL,
    status ENUM('draft','pending','published','archived') NOT NULL DEFAULT 'draft',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_mantras_status (status)
) ENGINE=InnoDB;

CREATE TABLE mantra_situations (
    mantra_id INT UNSIGNED NOT NULL,
    situation_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (mantra_id, situation_id),
    FOREIGN KEY (mantra_id) REFERENCES mantras(id) ON DELETE CASCADE,
    FOREIGN KEY (situation_id) REFERENCES situations(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE mantra_teachings (
    mantra_id INT UNSIGNED NOT NULL,
    teaching_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (mantra_id, teaching_id),
    FOREIGN KEY (mantra_id) REFERENCES mantras(id) ON DELETE CASCADE,
    FOREIGN KEY (teaching_id) REFERENCES teachings(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE mantra_gitas (
    mantra_id INT UNSIGNED NOT NULL,
    gita_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (mantra_id, gita_id),
    FOREIGN KEY (mantra_id) REFERENCES mantras(id) ON DELETE CASCADE,
    FOREIGN KEY (gita_id) REFERENCES gitas(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------------------------------- daily wisdom

DROP TABLE IF EXISTS daily_wisdom;

CREATE TABLE daily_wisdom (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    wisdom_date DATE NOT NULL UNIQUE,
    verse_id INT UNSIGNED NOT NULL,
    title VARCHAR(255) NULL,
    short_message TEXT NULL,
    practical_action TEXT NULL,
    reflection_question VARCHAR(500) NULL,
    mantra_id INT UNSIGNED NULL,
    featured TINYINT(1) NOT NULL DEFAULT 0,
    status ENUM('draft','pending','published','archived') NOT NULL DEFAULT 'published',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (verse_id) REFERENCES verses(id) ON DELETE CASCADE,
    FOREIGN KEY (mantra_id) REFERENCES mantras(id) ON DELETE SET NULL,
    INDEX idx_daily_wisdom_date (wisdom_date)
) ENGINE=InnoDB;

-- --------------------------------------------------------- learning paths

DROP TABLE IF EXISTS learning_path_items;
DROP TABLE IF EXISTS learning_paths;

CREATE TABLE learning_paths (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(160) NOT NULL UNIQUE,
    level VARCHAR(60) NULL,
    description TEXT NULL,
    status ENUM('draft','pending','published','archived') NOT NULL DEFAULT 'draft',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE learning_path_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    learning_path_id INT UNSIGNED NOT NULL,
    item_type VARCHAR(30) NOT NULL,
    item_id INT UNSIGNED NOT NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    FOREIGN KEY (learning_path_id) REFERENCES learning_paths(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- --------------------------------------------------- user-generated data

DROP TABLE IF EXISTS reflections;
DROP TABLE IF EXISTS bookmarks;

CREATE TABLE bookmarks (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    item_type VARCHAR(30) NOT NULL,
    item_id INT UNSIGNED NOT NULL,
    list_name VARCHAR(100) NOT NULL DEFAULT 'default',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_bookmarks_item (item_type, item_id)
) ENGINE=InnoDB;

CREATE TABLE reflections (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    verse_id INT UNSIGNED NULL,
    understanding TEXT NULL,
    life_relation TEXT NULL,
    practice_commitment TEXT NULL,
    personal_note TEXT NULL,
    reflection_date DATE NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (verse_id) REFERENCES verses(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------ site admin

DROP TABLE IF EXISTS media;
DROP TABLE IF EXISTS pages;
DROP TABLE IF EXISTS seo_meta;
DROP TABLE IF EXISTS settings;
DROP TABLE IF EXISTS activity_logs;

CREATE TABLE media (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    file_name VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    size INT UNSIGNED NOT NULL,
    path VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255) NULL,
    caption VARCHAR(255) NULL,
    uploaded_by INT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE pages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(280) NOT NULL UNIQUE,
    content LONGTEXT NULL,
    meta_title VARCHAR(255) NULL,
    meta_description VARCHAR(500) NULL,
    status ENUM('draft','pending','published','archived') NOT NULL DEFAULT 'draft',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE seo_meta (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    entity_type VARCHAR(30) NOT NULL,
    entity_id INT UNSIGNED NOT NULL,
    meta_title VARCHAR(255) NULL,
    meta_description VARCHAR(500) NULL,
    canonical_url VARCHAR(255) NULL,
    og_image VARCHAR(255) NULL,
    UNIQUE KEY uniq_entity (entity_type, entity_id)
) ENGINE=InnoDB;

CREATE TABLE settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(150) NOT NULL UNIQUE,
    setting_value TEXT NULL,
    setting_group VARCHAR(60) NOT NULL DEFAULT 'general'
) ENGINE=InnoDB;

CREATE TABLE activity_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    action VARCHAR(60) NOT NULL,
    module VARCHAR(60) NOT NULL,
    record_id INT UNSIGNED NULL,
    description VARCHAR(500) NULL,
    ip_address VARCHAR(45) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_activity_created (created_at)
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;
