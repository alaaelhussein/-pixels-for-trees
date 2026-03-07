<?php
function go(string $path): void
{
    header("Location: " . $path);
    exit;
}

function safe_next_path(
    ?string $value,
    string $fallback = "grid.php"
): string {
    $next = trim((string) $value);

    if ($next === "") {
        return $fallback;
    }

    if (str_contains($next, "://")
        || str_contains($next, "\\")
        || str_starts_with($next, "//")) {
        return $fallback;
    }

    if (!preg_match('/^[A-Za-z0-9_\-.\/?=&%#]+$/', $next)) {
        return $fallback;
    }

    return ltrim($next, "/");
}

function current_path(): string
{
    $uri = (string) ($_SERVER["REQUEST_URI"] ?? "index.php");
    $path = parse_url($uri, PHP_URL_PATH);
    $query = parse_url($uri, PHP_URL_QUERY);
    $value = ltrim((string) $path, "/");

    if ($value === "") {
        $value = "index.php";
    }

    if (!is_string($query) || $query === "") {
        return $value;
    }

    return $value . "?" . $query;
}

function post_only(): void
{
    if (($_SERVER["REQUEST_METHOD"] ?? "GET") !== "POST") {
        http_response_code(405);
        exit;
    }
}

function json_out(array $data, int $code = 200): void
{
    http_response_code($code);
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($data);
    exit;
}
