<?php
// users.php — SQLite read layer for users

function row_to_user(array $row): array
{
    return [
        'id'        => $row['id'],
        'name'      => $row['name'],
        'email'     => $row['email'],
        'role'      => $row['role'],
        'password'  => $row['password'],
        'createdAt' => $row['created_at'],
    ];
}

function users_all(): array
{
    $rows = db()->query("SELECT * FROM users ORDER BY created_at ASC")->fetchAll();
    return array_map('row_to_user', $rows);
}

function users_save(array $items): void
{
    $pdo  = db();
    $stmt = $pdo->prepare("
        INSERT OR REPLACE INTO users (id, name, email, role, password, created_at)
        VALUES (:id, :name, :email, :role, :password, :created_at)
    ");
    foreach ($items as $u) {
        $stmt->execute([
            'id'         => $u['id'],
            'name'       => $u['name'],
            'email'      => $u['email'],
            'role'       => $u['role'],
            'password'   => $u['password'],
            'created_at' => $u['createdAt'] ?? date(DATE_ATOM),
        ]);
    }
}

function find_user_by_email(string $email): ?array
{
    $stmt = db()->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([strtolower($email)]);
    $row = $stmt->fetch();
    return $row ? row_to_user($row) : null;
}

function find_user(string $id): ?array
{
    $stmt = db()->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ? row_to_user($row) : null;
}
