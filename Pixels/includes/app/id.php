<?php
function make_id(string $prefix): string
{
    return $prefix . "_" . bin2hex(random_bytes(6));
}


// bin2hex() convertit des octets binaires en chaîne hexadécimale lisible. On l’utilise après random_bytes() pour obtenir un token aléatoire qui est sûr à stocker, afficher et comparer.