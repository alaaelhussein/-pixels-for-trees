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

    $donation["treeState"] = "";
    $donation["treeRef"] = "";
    replace_donation($donation);
    return $donation;
}
