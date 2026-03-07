<?php
function tree_call(array $cfg, array $donation): array
{
    if (!function_exists("curl_init")) {
        return ["ok" => false, "ref" => ""];
    }

    $body = json_encode([
        "donationId" => $donation["id"],
        "trees" => count(donation_pixels($donation)),
        "amount" => $donation["amount"],
    ]);
    $ch = curl_init($cfg["url"]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . $cfg["key"],
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    $text = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);
    $data = json_decode($text ?: "{}", true);
    return [
        "ok" => $code >= 200 && $code < 300,
        "ref" => $data["id"] ?? "",
    ];
}
