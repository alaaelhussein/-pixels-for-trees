<?php
function create_donation(array $user, array $pixels, string $message): array
{
    $items = donations_all();
    $donation = [
        "id" => make_id("don"),
        "userId" => $user["id"],
        "userName" => $user["name"],
        "amount" => count($pixels) * price_per_pixel(),
        "pixelsData" => $pixels,
        "message" => $message,
        "status" => "pending",
        "treeState" => "pending",
        "createdAt" => date(DATE_ATOM),
        "reservedUntil" => date(
            DATE_ATOM,
            time() + reservation_seconds()
        ),
    ];
    $items[] = $donation;
    donations_save($items);
    return $donation;
}

function replace_donation(array $next): void
{
    $items = [];

    foreach (donations_all() as $item) {
        $items[] = ($item["id"] ?? "") === $next["id"] ? $next : $item;
    }

    donations_save($items);
}

function delete_donation(string $id): void
{
    $items = array_values(array_filter(
        donations_all(),
        fn($item) => ($item["id"] ?? "") !== $id
    ));
    donations_save($items);
}

function reset_donations(): void
{
    donations_save([]);
}
