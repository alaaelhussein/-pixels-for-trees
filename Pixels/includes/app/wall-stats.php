<?php
require_once __DIR__ . "/wall.php";

function wall_stats(): array
{
    $users = users_all();
    $donations = donations_all();
    $pixels = confirmed_pixels();
    $amount = 0;

    foreach ($donations as $item) {
        if (($item["status"] ?? "") === "confirmed") {
            $amount += (int) ($item["amount"] ?? 0);
        }
    }

    return [
        "users" => count($users),
        "donors" => count(array_unique(array_column($donations, "userId"))),
        "amount" => $amount,
        "pixels" => count($pixels),
        "trees" => count($pixels),
    ];
}

function pixels_free(
    array $pixels,
    string $ignoreDonationId = ""
): bool
{
    $map = wall_map($ignoreDonationId);

    foreach ($pixels as $pixel) {
        $key = $pixel["x"] . "-" . $pixel["y"];
        if (isset($map[$key])) {
            return false;
        }
    }

    return true;
}
