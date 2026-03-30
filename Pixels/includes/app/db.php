<?php
function db(): PDO
{
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $path = data_path("pixels.db");
    $dir  = dirname($path);

    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $pdo = new PDO("sqlite:" . $path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec("PRAGMA journal_mode=WAL");

    db_migrate($pdo);

    return $pdo;
}

function db_migrate(PDO $pdo): void
{
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id         TEXT PRIMARY KEY,
            name       TEXT NOT NULL,
            email      TEXT NOT NULL UNIQUE,
            role       TEXT NOT NULL DEFAULT 'user',
            password   TEXT NOT NULL,
            created_at TEXT NOT NULL
        );

        CREATE TABLE IF NOT EXISTS donations (
            id             TEXT PRIMARY KEY,
            user_id        TEXT NOT NULL,
            user_name      TEXT NOT NULL,
            amount         INTEGER NOT NULL DEFAULT 0,
            pixels_data    TEXT NOT NULL DEFAULT '[]',
            message        TEXT NOT NULL DEFAULT '',
            status         TEXT NOT NULL DEFAULT 'pending',
            tree_state     TEXT NOT NULL DEFAULT 'pending',
            tree_ref       TEXT NOT NULL DEFAULT '',
            provider_id    TEXT NOT NULL DEFAULT '',
            confirmed_at   TEXT NOT NULL DEFAULT '',
            reserved_until TEXT NOT NULL DEFAULT '',
            created_at     TEXT NOT NULL,
            meta           TEXT NOT NULL DEFAULT '{}'
        );

        CREATE TABLE IF NOT EXISTS webhooks (
            id          INTEGER PRIMARY KEY AUTOINCREMENT,
            donation_id TEXT NOT NULL DEFAULT '',
            event       TEXT NOT NULL DEFAULT '',
            status      TEXT NOT NULL DEFAULT '',
            payload     TEXT NOT NULL DEFAULT '{}',
            created_at  TEXT NOT NULL
        );
    ");

    db_seed_users($pdo);
}

function db_seed_users(PDO $pdo): void
{
    $count = (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

    if ($count > 0) {
        return;
    }

    $now = date(DATE_ATOM);
    $stmt = $pdo->prepare("
        INSERT OR IGNORE INTO users (id, name, email, role, password, created_at)
        VALUES (:id, :name, :email, :role, :password, :created_at)
    ");

    $stmt->execute([
        "id"         => "usr_admin",
        "name"       => "Admin",
        "email"      => "admin@pixels.test",
        "role"       => "admin",
        "password"   => password_hash("admin123", PASSWORD_DEFAULT),
        "created_at" => $now,
    ]);

    $stmt->execute([
        "id"         => "usr_user",
        "name"       => "User",
        "email"      => "user@pixels.test",
        "role"       => "user",
        "password"   => password_hash("user123", PASSWORD_DEFAULT),
        "created_at" => $now,
    ]);
}
