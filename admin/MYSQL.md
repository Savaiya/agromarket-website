# Admin MySQL setup

The active admin uses MySQL through PHP PDO. It needs PHP's `pdo_mysql` extension; it has no Composer package dependencies. This admin uses a dedicated `agromarket_admin` database by default to avoid colliding with the separate legacy application in `OldAgaro/admin`.

## Create the database

Run `database.sql` using a MySQL account that can create databases and tables:

```powershell
mysql -u root -p -e "source database.sql"
```

Create a separate MySQL user with access to `agromarket_admin` for the web application. Do not use the database administrator account in the site.

For example, replace the password placeholder with a private, strong password:

```sql
CREATE USER 'agromarket_admin'@'127.0.0.1' IDENTIFIED BY 'replace-with-a-private-password';
GRANT SELECT, INSERT, UPDATE, DELETE ON agromarket_admin.* TO 'agromarket_admin'@'127.0.0.1';
```

## Configure the connection

Set these variables in the PHP/web-server process:

- `AGROMARKET_MYSQL_HOST` (defaults to `127.0.0.1`)
- `AGROMARKET_MYSQL_DATABASE` (defaults to `agromarket_admin`)
- `AGROMARKET_MYSQL_USERNAME` (required)
- `AGROMARKET_MYSQL_PASSWORD` (may be empty for a local development account)

Alternatively, return these keys from the project-local `config.local.php`: `mysql_host`, `mysql_database`, `mysql_username`, and `mysql_password`. Keep credentials out of source control.

Example for a local PowerShell process:

```powershell
$env:AGROMARKET_MYSQL_HOST = "127.0.0.1"
$env:AGROMARKET_MYSQL_DATABASE = "agromarket_admin"
$env:AGROMARKET_MYSQL_USERNAME = "agromarket_admin"
$env:AGROMARKET_MYSQL_PASSWORD = "set-a-private-password"
```

Restart the PHP/web-server process after configuring it. The connection is checked when the admin signs in. Create the first administrator with a password hash generated locally:

```powershell
php -r "echo bin2hex(random_bytes(16)), PHP_EOL; echo password_hash('replace-with-a-strong-password', PASSWORD_DEFAULT), PHP_EOL;"
```

Use those generated values in this SQL, along with the desired name and email:

```sql
INSERT INTO admins (id, name, email, password, role, status)
VALUES ('32-character-hex-id', 'Admin Name', 'admin@example.com', 'generated-password-hash', 'super_admin', 'active');
```

## Data migration

Changing the connection does not copy existing records. Export/import any existing admins, leads, categories, and blog posts into the corresponding tables before switching production traffic if those records must be retained. IDs remain 32-character hexadecimal strings, and deleting a blog category automatically unassigns its posts.

## Upgrade an existing database

Apply the following once using a database administrator account. It creates the tag catalog and many-to-many association tables, then migrates existing scalar category/tag values without deleting the legacy columns:

```sql
CREATE TABLE IF NOT EXISTS blog_tags (
	id CHAR(32) NOT NULL PRIMARY KEY,
	name VARCHAR(80) NOT NULL UNIQUE,
	slug VARCHAR(100) NOT NULL UNIQUE,
	description TEXT NULL,
	created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO blog_tags (id, name, slug, created_at, updated_at)
SELECT LOWER(REPLACE(UUID(), '-', '')), legacy.name,
	   CONCAT('legacy-', LOWER(REPLACE(UUID(), '-', ''))), UTC_TIMESTAMP(), UTC_TIMESTAMP()
FROM (
	SELECT DISTINCT TRIM(tag) AS name FROM blogs
	WHERE TRIM(COALESCE(tag, '')) <> ''
) AS legacy;

CREATE TABLE IF NOT EXISTS category_map (
	id CHAR(32) NOT NULL PRIMARY KEY,
	blog_id CHAR(32) NOT NULL,
	category_id CHAR(32) NOT NULL,
	created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	UNIQUE KEY uq_category_map_pair (blog_id, category_id),
	INDEX idx_category_map_category (category_id),
	CONSTRAINT fk_category_map_blog FOREIGN KEY (blog_id) REFERENCES blogs(id) ON DELETE CASCADE,
	CONSTRAINT fk_category_map_category FOREIGN KEY (category_id) REFERENCES blog_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS tag_map (
	id CHAR(32) NOT NULL PRIMARY KEY,
	blog_id CHAR(32) NOT NULL,
	tag_id CHAR(32) NOT NULL,
	created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	UNIQUE KEY uq_tag_map_pair (blog_id, tag_id),
	INDEX idx_tag_map_tag (tag_id),
	CONSTRAINT fk_tag_map_blog FOREIGN KEY (blog_id) REFERENCES blogs(id) ON DELETE CASCADE,
	CONSTRAINT fk_tag_map_tag FOREIGN KEY (tag_id) REFERENCES blog_tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO category_map (blog_id, category_id)
SELECT id, category_id FROM blogs WHERE category_id IS NOT NULL;

INSERT IGNORE INTO tag_map (blog_id, tag_id)
SELECT b.id, t.id FROM blogs b
INNER JOIN blog_tags t ON t.name = TRIM(b.tag)
WHERE TRIM(COALESCE(b.tag, '')) <> '';
```

Grant the application database user SELECT, INSERT, UPDATE, and DELETE privileges on both mapping tables and the tag catalog. Keep the legacy `blogs.category_id` and `blogs.tag` columns until any other consumers have been migrated.
