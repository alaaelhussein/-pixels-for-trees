<?php
function clean_name(string $value): string
{
    return trim(strip_tags($value));
}

function valid_email(string $value): bool
{
    return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
}

function valid_password(string $value): bool
{
    return strlen($value) >= 8;
}

function valid_color(string $value): bool
{
    return preg_match('/^#[0-9A-Fa-f]{6}$/', $value) === 1;
}

function valid_pixel(int $x, int $y): bool
{
    return $x >= 0 && $y >= 0
        && $x < grid_size() && $y < grid_size();
}
