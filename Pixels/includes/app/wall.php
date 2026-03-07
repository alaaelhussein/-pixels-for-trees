<?php
require_once __DIR__ . "/pending.php";

function donation_pixels(array $donation): array
{
    return $donation["pixelsData"] ?? [];
}

function confirmed_pixels(
    string $ignoreDonationId = ""
): array
{
    $items = [];

    foreach (donations_all() as $donation) {
        if (($donation["id"] ?? "") === $ignoreDonationId) {
            continue;
        }

        if (($donation["status"] ?? "") !== "confirmed") {
            continue;
        }

        foreach (donation_pixels($donation) as $pixel) {
            $items[] = $pixel + [
                "status" => "reserved",
                "username" => $donation["userName"],
                "amount" => $donation["amount"],
                "message" => $donation["message"],
            ];
        }
    }

    return $items;
}

function reserved_pixels(
    string $ignoreDonationId = ""
): array
{
    $items = [];

    foreach (donations_all() as $donation) {
        if (($donation["id"] ?? "") === $ignoreDonationId) {
            continue;
        }

        if (!donation_is_reserved($donation)) {
            continue;
        }

        foreach (donation_pixels($donation) as $pixel) {
            $items[] = $pixel + [
                "status" => "reserved",
                "username" => $donation["userName"],
                "amount" => $donation["amount"],
                "message" => $donation["message"],
                "reservedUntil" => $donation["reservedUntil"] ?? "",
            ];
        }
    }

    return $items;
}

function grid_pixels(
    string $ignoreDonationId = ""
): array
{
    return array_merge(
        confirmed_pixels($ignoreDonationId),
        reserved_pixels($ignoreDonationId)
    );
}

function wall_pixels(): array
{
    return confirmed_pixels();
}

function wall_map(string $ignoreDonationId = ""): array
{
    $map = [];

    foreach (grid_pixels($ignoreDonationId) as $pixel) {
        $map[$pixel["x"] . "-" . $pixel["y"]] = true;
    }

    return $map;
}
