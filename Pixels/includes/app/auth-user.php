<?php
function current_user(): ?array
{
    $token = $_COOKIE[auth_cookie()] ?? "";
    $data = is_string($token) ? jwt_read($token) : null;

    if (!is_array($data) || ($data["exp"] ?? 0) < time()) {
        return null;
    }

    return find_user($data["sub"] ?? "");
}

function require_user(): array
{
    $user = current_user();

    if (!$user) {
        go("login.php?next=" . urlencode(current_path()));
    }

    return $user;
}

function require_admin(): array
{
    $user = require_user();

    if (($user["role"] ?? "user") !== "admin") {
        go("index.php");
    }

    return $user;
}
