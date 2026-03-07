<?php
// users-write.php — SQLite write layer for users

function create_user(
    string $name,
    string $email,
    string $password
): array {
    $count = (int) db()->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $user  = [
        'id'        => make_id('usr'),
        'name'      => $name,
        'email'     => strtolower($email),
        'role'      => $count === 0 ? 'admin' : 'user',
        'password'  => password_hash($password, PASSWORD_DEFAULT),
        'createdAt' => date(DATE_ATOM),
    ];
    $stmt = db()->prepare("
        INSERT INTO users (id, name, email, role, password, created_at)
        VALUES (:id, :name, :email, :role, :password, :created_at)
    ");
    $stmt->execute([
        'id'         => $user['id'],
        'name'       => $user['name'],
        'email'      => $user['email'],
        'role'       => $user['role'],
        'password'   => $user['password'],
        'created_at' => $user['createdAt'],
    ]);
    return $user;
}

function user_public(array $user): array
{
    unset($user['password']);
    return $user;
}
