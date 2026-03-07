<?php
function users_all(): array
{
    return read_json_file(data_path("users.json"));
}

function users_save(array $items): void
{
    write_json_file(data_path("users.json"), $items);
}

function find_user_by_email(string $email): ?array
{
    foreach (users_all() as $user) {
        if (($user["email"] ?? "") === $email) {
            return $user;
        }
    }

    return null;
}

function find_user(string $id): ?array
{
    foreach (users_all() as $user) {
        if (($user["id"] ?? "") === $id) {
            return $user;
        }
    }

    return null;
}
