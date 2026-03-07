<?php
function jwt_b64e(string $text): string
{
    return rtrim(strtr(base64_encode($text), "+/", "-_"), "=");
}

function jwt_b64d(string $text): string
{
    $pad = strlen($text) % 4;

    if ($pad > 0) {
        $text .= str_repeat("=", 4 - $pad);
    }

    return base64_decode(strtr($text, "-_", "+/")) ?: "";
}

function jwt_make(array $payload): string
{
    $head = jwt_b64e(json_encode(["alg" => "HS256", "typ" => "JWT"]));
    $body = jwt_b64e(json_encode($payload));
    $sign = hash_hmac("sha256", $head . "." . $body, jwt_secret(), true);
    return $head . "." . $body . "." . jwt_b64e($sign);
}

function jwt_read(string $token): ?array
{
    $parts = explode(".", $token);

    if (count($parts) !== 3) {
        return null;
    }

    $sign = hash_hmac("sha256", $parts[0] . "." . $parts[1], jwt_secret(), true);

    if (!hash_equals(jwt_b64e($sign), $parts[2])) {
        return null;
    }

    $data = json_decode(jwt_b64d($parts[1]), true);
    return is_array($data) ? $data : null;
}
