<?php
function donations_all(): array
{
    return read_json_file(data_path("donations.json"));
}

function donations_save(array $items): void
{
    write_json_file(data_path("donations.json"), $items);
}

function find_donation(string $id): ?array
{
    foreach (donations_all() as $item) {
        if (($item["id"] ?? "") === $id) {
            return $item;
        }
    }

    return null;
}

function user_donations(string $userId): array
{
    return array_values(array_filter(donations_all(),
        fn($item) => ($item["userId"] ?? "") === $userId));
}
