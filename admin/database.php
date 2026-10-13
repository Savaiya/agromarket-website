<?php

class Database
{
    private static $instance;
    private $connection;

    private function __construct()
    {
        if (AGROMARKET_MYSQL_HOST === '' || AGROMARKET_MYSQL_DATABASE === '' || AGROMARKET_MYSQL_USERNAME === '') {
            throw new RuntimeException(
                'Admin database configuration is missing. Set the AGROMARKET_MYSQL_HOST, AGROMARKET_MYSQL_DATABASE, and AGROMARKET_MYSQL_USERNAME settings.'
            );
        }

        try {
            $dsn = 'mysql:host=' . AGROMARKET_MYSQL_HOST
                . ';dbname=' . AGROMARKET_MYSQL_DATABASE
                . ';charset=utf8mb4';
            $this->connection = new PDO($dsn, AGROMARKET_MYSQL_USERNAME, AGROMARKET_MYSQL_PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_TIMEOUT => 5,
                \Pdo\MySql::ATTR_FOUND_ROWS => true,
            ]);
            $this->connection->exec("SET time_zone = '+00:00'");
        } catch (PDOException $exception) {
            throw new RuntimeException('Admin MySQL connection failed. Check the database settings and access permissions.', 0, $exception);
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
        throw new RuntimeException('Cannot unserialize the database singleton.');
    }
}

function getDB(): PDO
{
    return Database::getInstance()->getConnection();
}

function newAdminId(): string
{
    return bin2hex(random_bytes(16));
}

function isAdminId($id): bool
{
    return is_string($id) && preg_match('/^[a-f0-9]{32}$/', $id) === 1;
}

function adminFormatDate($value, string $format): string
{
    if ($value instanceof DateTimeInterface) {
        return $value->format($format);
    }

    if (!is_string($value) && !is_numeric($value)) {
        return '';
    }

    try {
        $date = is_numeric($value)
            ? (new DateTimeImmutable('@' . (int) $value))->setTimezone(new DateTimeZone('UTC'))
            : new DateTimeImmutable((string) $value, new DateTimeZone('UTC'));
    } catch (Exception $exception) {
        return '';
    }

    return $date->format($format);
}
