<?php
function auth_cookie(): string
{
    return "pixels_auth";
}

function login_user(array $user): void
{
    $payload = [
        "sub" => $user["id"],
        "role" => $user["role"],
        "exp" => time() + 604800,
    ];
    setcookie(auth_cookie(), jwt_make($payload), [
        "expires" => $payload["exp"],
        "path" => "/",
        "httponly" => true,
        "samesite" => "Lax",
    ]);
}

function logout_user(): void
{
    setcookie(auth_cookie(), "", time() - 3600, "/");
}
