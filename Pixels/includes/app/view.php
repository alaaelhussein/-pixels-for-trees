<?php
function safe_json(array $data): string
{
    return json_encode($data,
        JSON_HEX_TAG |
        JSON_HEX_APOS |
        JSON_HEX_AMP |
        JSON_HEX_QUOT
    ) ?: "[]";
}

function money(int $value): string
{
    return number_format($value, 0, ",", " ") . " $";
}

function webhook_badge(string $value): string
{
    return $value === "done"
        ? "bg-green-100 text-green-800"
        : "bg-yellow-100 text-yellow-800";
}
