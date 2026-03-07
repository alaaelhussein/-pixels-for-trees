<?php
function root_path(string $path = ""): string
{
    $base = dirname(__DIR__, 2);

    if ($path === "") {
        return $base;
    }

    return $base . "/" . ltrim($path, "/");
}

function data_path(string $name): string
{
    return root_path("data/" . $name);
}
