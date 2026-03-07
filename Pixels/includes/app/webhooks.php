<?php
function webhooks_all(): array
{
    return read_json_file(data_path("webhooks.json"));
}

function log_webhook(array $item): void
{
    $items = webhooks_all();
    $items[] = $item;
    write_json_file(data_path("webhooks.json"), $items);
}
