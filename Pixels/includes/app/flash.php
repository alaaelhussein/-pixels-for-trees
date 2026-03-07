<?php
function set_flash(string $type, string $text): void
{
    $_SESSION["flash"] = [
        "type" => $type,
        "text" => $text,
    ];
}

function pull_flash(): ?array
{
    $flash = $_SESSION["flash"] ?? null;
    unset($_SESSION["flash"]);
    return $flash;
}
