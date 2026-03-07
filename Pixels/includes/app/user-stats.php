<?php
function user_stats(array $user): array
{
    $items = user_donations($user["id"]);
    $total = 0;
    $pixels = [];

    foreach ($items as $item) {
        if (($item["status"] ?? "") !== "confirmed") {
            continue;
        }

        $total += (int) ($item["amount"] ?? 0);
        $pixels = array_merge($pixels, donation_pixels($item));
    }

    return [
        "amount" => $total,
        "pixels" => count($pixels),
        "trees" => count($pixels),
        "items" => $items,
        "wall" => $pixels,
    ];
}
