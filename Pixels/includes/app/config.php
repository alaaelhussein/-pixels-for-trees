<?php
function env_value(string $key, string $fallback): string
{
    $value = getenv($key);

    if ($value === false || $value === "") {
        return $fallback;
    }

    return $value;
}

function grid_size(): int
{
    return 1000;
}

function price_per_pixel(): int
{
    return 2;
}

function reservation_seconds(): int
{
    return 300;
}

function jwt_secret(): string
{
    return env_value("PIXELS_JWT_SECRET", "local-dev-secret");
}

function app_url(): string
{
    $value = env_value("PIXELS_APP_URL", "");

    if ($value !== "") {
        return rtrim($value, "/");
    }

    $https = $_SERVER["HTTPS"] ?? "off";
    $scheme = $https !== "off" ? "https" : "http";
    $host = $_SERVER["HTTP_HOST"] ?? "localhost:8000";
    return $scheme . "://" . $host;
}

function webhook_secret(): string
{
    return env_value("PIXELS_WEBHOOK_SECRET", "local-webhook");
}

function every_url(): string
{
    return env_value("PIXELS_EVERY_URL", "https://www.every.org");
}

function every_nonprofit(): string
{
    return env_value("PIXELS_EVERY_NONPROFIT", "plant-with-purpose");
}

function every_webhook_token(): string
{
    return env_value("PIXELS_EVERY_WEBHOOK_TOKEN", "local-dev-webhook");
}

function every_methods(): string
{
    return env_value("PIXELS_EVERY_METHODS", "card,bank,paypal,pay");
}

function every_designation(): string
{
    return env_value("PIXELS_EVERY_DESIGNATION", "Pixels for Trees");
}

function every_theme_color(): string
{
    return env_value("PIXELS_EVERY_THEME_COLOR", "FB923C");
}

function cors_origin(): string
{
    return env_value("PIXELS_CORS_ORIGIN", "http://localhost:8000");
}
