<?php
function confirm_donation(
    array $donation,
    string $ref,
    array $extra = []
): array
{
    $donation["status"] = "confirmed";
    $donation["providerId"] = $ref;
    $donation["confirmedAt"] = date(DATE_ATOM);

    foreach ($extra as $key => $value) {
        $donation[$key] = $value;
    }

    $tree = send_tree_order($donation);
    $donation["treeState"] = $tree["state"];
    $donation["treeRef"] = $tree["ref"];
    replace_donation($donation);
    return $donation;
}

function retry_tree_sync(): void
{
    foreach (donations_all() as $item) {
        if (($item["status"] ?? "") !== "confirmed") {
            continue;
        }

        if (($item["treeState"] ?? "") === "done") {
            continue;
        }

        $tree = send_tree_order($item);
        $item["treeState"] = $tree["state"];
        $item["treeRef"] = $tree["ref"];
        replace_donation($item);
    }
}
