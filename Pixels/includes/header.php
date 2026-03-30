<?php
require_once __DIR__ . "/app/boot.php";
require_once __DIR__ . "/icons.php";

$pageTitle = $pageTitle ?? "Pixels for Trees";
$currentUser = current_user();
$flash = pull_flash();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1"
  />
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <script>
    window.tailwind = window.tailwind || {};
    window.tailwind.config = {
      darkMode: "class",
    };
  </script>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    (function applyInitialTheme() {
      const key = "pixels.theme.mode";
      const savedTheme = localStorage.getItem(key);
      const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
      const shouldUseDark = savedTheme === "dark" || (savedTheme === null && prefersDark);

      if (shouldUseDark) {
        document.documentElement.classList.add("dark");
      }
    })();
  </script>
  <script src="assets/js/dark-mode.js"></script>
  <style>
    html {
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    body,
    header,
    main,
    #cell-drawer > div,
    #grid-info-panel {
      transition: background-color 0.25s ease, color 0.25s ease, border-color 0.25s ease;
    }

    html.dark body {
      background-color: #030712;
      color: #f3f4f6;
    }

    html.dark header {
      background-color: #111827;
      border-color: #1f2937;
    }

    html.dark header a {
      color: #d1d5db;
    }

    html.dark header a:hover {
      color: #ffffff;
    }

    html.dark #grid-info-panel,
    html.dark #cell-drawer > div {
      background-color: #111827;
      border-color: #1f2937;
      color: #f3f4f6;
    }

    html.dark #pixel-message,
    html.dark #pixel-color-text {
      background-color: #111827;
      border-color: #1f2937;
      color: #f3f4f6;
    }

    .drawer-pill { width: 40px; height: 4px; border-radius: 9999px; background-color: #d1d5db; }
    html.dark .drawer-pill { background-color: #4b5563; }

    html.dark .ui-btn {
      background-color: #111827;
      color: #f3f4f6;
    }

    #grid-info-panel h1 {
      color: #111827;
    }

    html.dark #grid-info-panel h1 {
      color: #f3f4f6;
    }

    #leaderboard-panel {
      background-color: #ffffff;
      color: #111827;
      border: 1px solid #e5e7eb;
    }
    #leaderboard-panel h2 {
      color: #111827;
    }
    #leaderboard-panel thead th {
      color: #6b7280;
    }
    #leaderboard-panel tbody tr {
      border-color: #f3f4f6;
    }
    #leaderboard-panel tbody tr:hover {
      background-color: #f9fafb;
    }
    #leaderboard-panel tbody td {
      color: #374151;
    }
    #leaderboard-panel .panel-header {
      border-color: #e5e7eb;
    }
    #leaderboard-panel .lb-close-btn {
      color: #9ca3af;
    }
    #leaderboard-panel .lb-close-btn:hover {
      color: #374151;
    }

    html.dark #leaderboard-panel {
      background-color: #111827;
      border-color: #1f2937;
      color: #f3f4f6;
    }
    html.dark #leaderboard-panel h2 {
      color: #f3f4f6;
    }
    html.dark #leaderboard-panel thead th {
      color: #9ca3af;
    }
    html.dark #leaderboard-panel tbody tr {
      border-color: #1f2937;
    }
    html.dark #leaderboard-panel tbody tr:hover {
      background-color: #1f2937;
    }
    html.dark #leaderboard-panel tbody td {
      color: #d1d5db;
    }
    html.dark #leaderboard-panel .panel-header {
      border-color: #1f2937;
    }
    html.dark #leaderboard-panel .lb-close-btn {
      color: #6b7280;
    }
    html.dark #leaderboard-panel .lb-close-btn:hover {
      color: #f3f4f6;
    }
  </style>
</head>
<body class="bg-white text-gray-900 dark:bg-gray-950 dark:text-gray-100">
  <header class="bg-white border-b border-gray-200">
    <div
      class="max-w-7xl mx-auto px-4 sm:px-6
      lg:px-8 py-4"
    >
      <div class="flex items-center justify-between">
        <a href="index.php" class="flex items-center">
          <img
            src="assets/media/navbar-logo.png"
            alt="Pixels for Trees"
            style="display:block;height:40px;width:auto;"
          />
        </a>
        <nav class="flex items-center gap-6">
          <a
            href="index.php"
            class="text-gray-600 hover:text-gray-900
            transition"
          >
            Home
          </a>
          <a
            href="grid.php"
            class="text-gray-600 hover:text-gray-900
            transition"
          >
            Grid
          </a>
          <?php if ($currentUser && ($currentUser['role'] ?? '') === 'admin'): ?>
            <a
              href="admin.php"
              class="text-gray-600 hover:text-gray-900 transition"
            >
              Dashboard
            </a>
            <a
              href="admin.php#donations"
              class="text-gray-600 hover:text-gray-900 transition"
            >
              Donations
            </a>
            <a
              href="admin.php#users"
              class="text-gray-600 hover:text-gray-900 transition"
            >
              Users
            </a>
            <a
              href="admin.php#webhooks"
              class="text-gray-600 hover:text-gray-900 transition"
            >
              Webhooks
            </a>
            <span class="text-xs font-semibold bg-orange-100 text-orange-700 rounded-full px-2 py-1">
              Admin
            </span>
            <a
              href="logout.php"
              class="text-gray-600 hover:text-gray-900 transition"
            >
              Log out
            </a>
          <?php elseif ($currentUser): ?>
            <a
              href="logout.php"
              class="text-gray-600 hover:text-gray-900
              transition"
            >
              Log out
            </a>
          <?php else: ?>
            <a
              href="login.php"
              class="text-gray-600
              hover:text-gray-900 transition"
            >
              Log in
            </a>
          <?php endif; ?>
          <button
            id="theme-toggle"
            class="flex items-center justify-center w-8 h-8 rounded focus:outline-none text-gray-600 dark:text-gray-300"
            aria-label="Toggle dark mode"
          >
            <span id="theme-icon-sun"><?= icon_sun('w-5 h-5') ?></span>
            <span id="theme-icon-moon" class="hidden"><?= icon_moon('w-5 h-5') ?></span>
          </button>
        </nav>
      </div>
    </div>
  </header>
  <?php if ($flash): ?>
    <div class="bg-gray-900 text-white px-4 py-3 text-center">
      <?= htmlspecialchars($flash['text'] ?? '') ?>
    </div>
  <?php endif; ?>
