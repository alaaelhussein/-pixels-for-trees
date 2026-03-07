<?php
function create_user(
    string $name,
    string $email,
    string $password
): array {
    $items = users_all();
    $role = count($items) === 0 ? "admin" : "user";
    $user = [
        "id" => make_id("usr"),
        "name" => $name,
        "email" => strtolower($email),
        "role" => $role,
        "password" => password_hash($password, PASSWORD_DEFAULT),
        "createdAt" => date(DATE_ATOM),
    ];
    $items[] = $user;
    users_save($items);
    return $user;
}

function user_public(array $user): array
{
    unset($user["password"]);
    return $user;
}
