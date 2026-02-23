<?php
require_once __DIR__ . "/icons.php";

$isLoggedIn = $isLoggedIn ?? false;
$username = $username ?? "";
$pageTitle = $pageTitle ?? "Pixels for Trees";
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-900">
  <header class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <div class="flex items-center justify-between">
        <a href="index.php" class="flex items-center gap-2">
          <?= icon_tree_pine("w-8 h-8 text-green-600") ?>
          <span class="text-xl font-semibold text-gray-900">Pixels for Trees</span>
        </a>
        <nav class="flex items-center gap-6">
          <a href="index.php" class="text-gray-600 hover:text-gray-900 transition">Accueil</a>
          <a href="index.php#how-it-works" class="text-gray-600 hover:text-gray-900 transition">Comment ça marche</a>
          <?php if ($isLoggedIn): ?>
            <a href="impact.php" class="text-gray-600 hover:text-gray-900 transition">Mon impact</a>
            <span class="text-gray-700">
              Connecté en tant que <span class="font-medium"><?= htmlspecialchars($username) ?></span>
            </span>
          <?php else: ?>
            <a href="grid.php" class="text-gray-600 hover:text-gray-900 transition">Mon compte</a>
          <?php endif; ?>
        </nav>
      </div>
    </div>
  </header>
