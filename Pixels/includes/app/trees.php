<?php
function tree_config(): array
{
    return [
        "url" => env_value("PIXELS_TREE_URL", ""),
        "key" => env_value("PIXELS_TREE_KEY", ""),
    ];
}

function send_tree_order(array $donation): array
{
    $cfg = tree_config();

    if ($cfg["url"] === "" || $cfg["key"] === "") {
        return ["state" => "skipped", "ref" => ""];
    }

    return tree_retry($cfg, $donation, 0);
}

function tree_retry(array $cfg, array $donation, int $try): array
{
    $result = tree_call($cfg, $donation);

    if ($result["ok"] || $try >= 2) {
        return $result["ok"]
            ? ["state" => "done", "ref" => $result["ref"]]
            : ["state" => "failed", "ref" => ""];
    }

    sleep((int) pow(2, $try));
    return tree_retry($cfg, $donation, $try + 1);
}
