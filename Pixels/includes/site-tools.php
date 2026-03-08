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
