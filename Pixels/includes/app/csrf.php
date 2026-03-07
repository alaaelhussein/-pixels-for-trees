<?php
function csrf_token(): string
{
    if (!isset($_SESSION["csrf"])) {
        $_SESSION["csrf"] = bin2hex(random_bytes(16));
    }

    return $_SESSION["csrf"];
}

function csrf_ok(?string $value): bool
{
    $token = $_SESSION["csrf"] ?? "";
    return is_string($value) && $token !== ""
        && hash_equals($token, $value);
}
