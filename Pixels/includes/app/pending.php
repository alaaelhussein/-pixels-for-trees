<?php
function donation_reserved_until(array $item): int
{
    $value = strtotime((string) ($item["reservedUntil"] ?? ""));

    if ($value !== false) {
        return $value;
    }

    $created = strtotime((string) ($item["createdAt"] ?? ""));

    if ($created === false) {
        return 0;
    }

    return $created + reservation_seconds();
}

function donation_is_reserved(array $item): bool
{
    return ($item["status"] ?? "") === "pending"
        && donation_reserved_until($item) > time();
}

function donation_reserve_left(array $item): int
{
    return max(0, donation_reserved_until($item) - time());
}

function pending_donations(): array
{
    return array_values(array_filter(donations_all(),
        fn($item) => donation_is_reserved($item)));
}
