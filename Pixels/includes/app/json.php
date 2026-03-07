<?php
function read_json_file(string $path): array
{
    if (!is_file($path)) {
        return [];
    }

    $text = file_get_contents($path);
    $data = json_decode($text ?: "[]", true);

    if (!is_array($data)) {
        return [];
    }

    return $data;
}

function write_json_file(string $path, array $items): void
{
    $json = json_encode($items, JSON_PRETTY_PRINT);
    file_put_contents($path, $json . PHP_EOL, LOCK_EX);
}
