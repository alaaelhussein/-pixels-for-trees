<?php
function join_classes(array $parts): string
{
    return implode(" ", $parts);
}

function format_date_fr(
    string $value
): string {
    $date = new DateTime($value);

    if (class_exists("IntlDateFormatter")) {
        $formatter = new IntlDateFormatter(
            "en_US",
            IntlDateFormatter::LONG,
            IntlDateFormatter::NONE
        );

        return $formatter->format($date);
    }

    return $date->format("m/d/Y");
}

function first_letter(string $value): string
{
    if (function_exists("mb_substr")) {
        return mb_substr($value, 0, 1);
    }

    return substr($value, 0, 1);
}

function sum_amount(array $items): int
{
    $total = 0;

    foreach ($items as $item) {
        $total += $item["amount"];
    }

    return $total;
}

function sum_pixels(array $items): int
{
    $total = 0;

    foreach ($items as $item) {
        $total += $item["pixels"];
    }

    return $total;
}

function count_donors(array $items): int
{
    $names = [];

    foreach ($items as $item) {
        $names[] = $item["username"];
    }

    return count(array_unique($names));
}
