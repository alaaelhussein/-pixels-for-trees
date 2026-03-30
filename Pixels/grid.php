<?php
require_once __DIR__ . "/includes/app/boot.php";
require_once __DIR__ . "/includes/site-data.php";
require_once __DIR__ . "/includes/site-tools.php";

$user = current_user();
$pageTitle = "Choose your pixels";
$wallPixels = grid_pixels();
$canCheckout = $user !== null;
$reserveMinutes = (int) ceil(reservation_seconds() / 60);
$loginNext = "login.php?next=" . urlencode("recap.php");
$leaderboard = leaderboard_players(50);

require_once __DIR__ . "/includes/header.php";
?>
<div class="bg-white dark:bg-black" style="height: calc(100vh - 73px);">
  <main class="relative h-full overflow-hidden">
    <div id="grid-container" class="absolute inset-0"></div>

    <div class="absolute left-3 top-3 z-30 flex flex-col gap-2">
      <button id="zoom-in-btn" type="button" title="Zoom in" class="ui-btn h-10 w-10 rounded-full bg-white text-xl text-gray-900 shadow-lg">
        +
      </button>
      <button id="zoom-out-btn" type="button" title="Zoom out" class="ui-btn h-10 w-10 rounded-full bg-white text-xl text-gray-900 shadow-lg">
        −
      </button>
      <button id="reset-view-btn" type="button" title="Reset view" class="ui-btn h-10 w-10 rounded-full bg-white text-sm font-semibold text-gray-900 shadow-lg">
        ↺
      </button>
      <button id="export-png-btn" type="button" title="Export PNG" class="ui-btn rounded-full bg-white px-3 py-2 text-xs font-semibold text-gray-900 shadow-lg">
        PNG
      </button>
    </div>

    <div class="absolute right-3 top-3 z-40">
      <div class="relative flex flex-col items-end gap-2">
        <?php if ($user): ?>
          <a href="logout.php" class="ui-btn rounded-xl bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-lg">
            Log out
          </a>
        <?php else: ?>
          <a href="<?= htmlspecialchars($loginNext) ?>" class="rounded-xl bg-orange-500 px-4 py-2 text-sm font-semibold text-white shadow-lg hover:bg-orange-400 transition">
            Log in
          </a>
        <?php endif; ?>
        <button id="info-button" type="button" title="Info" class="ui-btn h-10 w-10 rounded-full bg-white text-sm font-bold text-gray-900 shadow-lg">
          i
        </button>
        <button id="leaderboard-button" type="button" title="Leaderboard" class="ui-btn h-10 w-10 rounded-full bg-white text-gray-900 shadow-lg flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor" class="w-5 h-5"><path d="M160-200h160v-320H160v320Zm240 0h160v-560H400v560Zm240 0h160v-240H640v240ZM80-120v-480h240v-240h320v320h240v400H80Z"/></svg>
        </button>
        <div id="leaderboard-panel" style="display:none; flex-direction:column; max-height:80vh;" class="absolute top-0 right-full mr-3 w-80 rounded-2xl shadow-2xl">
          <div class="panel-header flex items-center justify-between gap-2 px-4 pt-4 pb-3 border-b shrink-0">
            <div class="flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor" class="w-5 h-5"><path d="M160-200h160v-320H160v320Zm240 0h160v-560H400v560Zm240 0h160v-240H640v240ZM80-120v-480h240v-240h320v320h240v400H80Z"/></svg>
              <h2 class="text-base font-bold">Leaderboard</h2>
            </div>
            <button id="leaderboard-close-btn" type="button" aria-label="Close leaderboard" class="lb-close-btn flex h-7 w-7 items-center justify-center rounded-full transition text-lg leading-none">&times;</button>
          </div>
          <div class="overflow-y-auto">
            <?php if (empty($leaderboard)): ?>
              <p class="px-4 py-6 text-sm text-center">No data yet. Be the first!</p>
            <?php else: ?>
              <table class="w-full text-sm">
                <thead>
                  <tr class="text-xs uppercase tracking-wide">
                    <th class="px-4 py-2 text-center w-10">#</th>
                    <th class="px-2 py-2 text-left">Player</th>
                    <th class="px-4 py-2 text-right">Pixels</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($leaderboard as $i => $row): ?>
                    <tr class="border-t transition">
                      <td class="px-4 py-2 text-center font-medium">
                        <?php if ($i === 0): ?>
                          <span class="text-yellow-500 font-bold">1</span>
                        <?php elseif ($i === 1): ?>
                          <span style="color:#9ca3af" class="font-bold">2</span>
                        <?php elseif ($i === 2): ?>
                          <span class="text-amber-600 font-bold">3</span>
                        <?php else: ?>
                          <?= $i + 1 ?>
                        <?php endif; ?>
                      </td>
                      <td class="px-2 py-2 font-medium truncate max-w-0 w-full">
                        <?= htmlspecialchars($row['user_name']) ?>
                      </td>
                      <td class="px-4 py-2 text-right font-semibold">
                        <?= number_format((int) $row['pixel_count']) ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php endif; ?>
          </div>
        </div>
        <div id="grid-info-panel" class="absolute top-full right-0 mt-2 w-72 rounded-2xl bg-white p-4 text-sm text-gray-700 shadow-2xl">
          <div class="flex items-start justify-between gap-2 mb-2">
            <h1 class="text-lg font-semibold">Choose your pixels</h1>
            <button id="info-close-btn" type="button" aria-label="Close info panel" class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">&times;</button>
          </div>
          <p class="mt-2">
            The view starts at the top-left corner.
            It opens at about 100 &times; 50 cells.
          </p>
          <p class="mt-2">
            Click a free cell to open the drawer.
            Reserved cells are locked for other people.
            You can zoom out to see more of the wall.
          </p>
          <p class="mt-2 text-blue-700 dark:text-blue-400">
            <?= $canCheckout
                ? "Reservation starts when you continue and lasts {$reserveMinutes} minutes."
                : "Guests can test colors first. Reservation starts after log in." ?>
          </p>
          <div class="mt-3 flex flex-wrap gap-3 text-xs font-medium text-gray-600 dark:text-gray-400">
            <?php foreach ($gridLegend as $item): ?>
              <div class="flex items-center gap-2">
                <div class="<?= join_classes($item['box']) ?>"></div>
                <span><?= $item["label"] ?></span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <div id="cell-drawer" class="hidden absolute bottom-0 left-0 z-50 w-full px-3 pb-0 sm:left-1/2 sm:max-w-md sm:-translate-x-1/2 md:max-w-lg">
      <div class="w-full rounded-t-2xl border border-gray-200 bg-white shadow-2xl sm:mb-3 sm:rounded-2xl dark:border-gray-700 dark:bg-gray-800">
        <div id="drawer-drag-handle" class="cursor-move touch-none select-none border-b border-gray-200 px-4 pb-3 dark:border-gray-700">
          <div class="flex justify-center pt-2 pb-1"><div class="drawer-pill"></div></div>
          <div class="flex items-center justify-between gap-3">
            <div class="min-w-0">
              <div class="flex items-center gap-2">
                <span id="drawer-status" class="rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-900 dark:text-blue-200">
                  Selected
                </span>
                <span id="active-cell" class="text-sm font-semibold text-gray-900 dark:text-white">
                  Cell —
                </span>
              </div>
              <p id="drawer-meta" class="mt-1 text-xs text-gray-500 dark:text-gray-400"></p>
            </div>
            <button id="close-drawer-button" type="button" class="h-8 w-8 rounded-full border border-gray-200 text-sm text-gray-700 dark:border-gray-600 dark:text-gray-300">
              ×
            </button>
          </div>
        </div>

        <div class="px-4 py-3">
          <div class="flex items-center gap-3">
            <div id="drawer-color-preview" class="h-12 w-12 rounded-xl border border-gray-200 bg-orange-400 dark:border-gray-600"></div>
            <div>
              <p id="drawer-note" class="text-sm text-gray-700 dark:text-gray-300"></p>
              <p id="drawer-owner" class="mt-1 text-xs text-gray-500 dark:text-gray-400"></p>
            </div>
          </div>

          <div id="drawer-editor" class="mt-4">
            <p class="mb-2 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Pick a color</p>
            <div id="color-swatches" class="flex flex-wrap gap-2 mb-3">
              <button type="button" class="color-swatch h-8 w-8 rounded-md border-2 border-transparent transition hover:scale-110" style="background:#FB923C" data-color="#FB923C"></button>
              <button type="button" class="color-swatch h-8 w-8 rounded-md border-2 border-transparent transition hover:scale-110" style="background:#EF4444" data-color="#EF4444"></button>
              <button type="button" class="color-swatch h-8 w-8 rounded-md border-2 border-transparent transition hover:scale-110" style="background:#EC4899" data-color="#EC4899"></button>
              <button type="button" class="color-swatch h-8 w-8 rounded-md border-2 border-transparent transition hover:scale-110" style="background:#A855F7" data-color="#A855F7"></button>
              <button type="button" class="color-swatch h-8 w-8 rounded-md border-2 border-transparent transition hover:scale-110" style="background:#6366F1" data-color="#6366F1"></button>
              <button type="button" class="color-swatch h-8 w-8 rounded-md border-2 border-transparent transition hover:scale-110" style="background:#3B82F6" data-color="#3B82F6"></button>
              <button type="button" class="color-swatch h-8 w-8 rounded-md border-2 border-transparent transition hover:scale-110" style="background:#06B6D4" data-color="#06B6D4"></button>
              <button type="button" class="color-swatch h-8 w-8 rounded-md border-2 border-transparent transition hover:scale-110" style="background:#22C55E" data-color="#22C55E"></button>
              <button type="button" class="color-swatch h-8 w-8 rounded-md border-2 border-transparent transition hover:scale-110" style="background:#84CC16" data-color="#84CC16"></button>
              <button type="button" class="color-swatch h-8 w-8 rounded-md border-2 border-transparent transition hover:scale-110" style="background:#EAB308" data-color="#EAB308"></button>
              <button type="button" class="color-swatch h-8 w-8 rounded-md border-2 border-transparent transition hover:scale-110" style="background:#F1F5F9" data-color="#F1F5F9"></button>
              <button type="button" class="color-swatch h-8 w-8 rounded-md border-2 border-transparent transition hover:scale-110" style="background:#1E293B" data-color="#1E293B"></button>
              <input type="color" id="pixel-color-native" value="#FB923C" title="Custom color" class="h-8 w-8 rounded-md cursor-pointer" style="padding:2px;border:2px dashed #9ca3af">
            </div>
            <input id="pixel-color-text" type="text" value="#FB923C" maxlength="7" placeholder="#FB923C" class="w-full rounded-lg border border-gray-200 px-3 py-2 font-mono text-sm uppercase dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
            <label for="pixel-message" class="mt-4 block text-sm font-medium text-gray-700 dark:text-gray-300">
              Public message
            </label>
            <textarea id="pixel-message" class="mt-2 w-full rounded-lg border border-gray-200 px-4 py-3 dark:border-gray-600 dark:bg-gray-700 dark:text-white" rows="3" maxlength="120"></textarea>
          </div>

          <div id="drawer-locked" class="hidden mt-4 rounded-xl bg-slate-50 p-4 text-sm text-slate-700 dark:bg-slate-900 dark:text-slate-300">
            <p id="drawer-locked-text"></p>
          </div>

          <div class="mt-4 grid grid-cols-3 gap-3 rounded-xl bg-gray-50 p-4 text-sm text-gray-700 dark:bg-gray-700 dark:text-gray-100">
            <div>
              <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Selected</p>
              <p id="selected-count" class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">0</p>
            </div>
            <div>
              <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Amount</p>
              <p id="total-amount" class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">0 $</p>
            </div>
            <div>
              <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Trees</p>
              <p id="total-trees" class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">0</p>
            </div>
          </div>

          <div class="mt-4 flex flex-wrap gap-2">
            <button id="remove-active-button" type="button" class="rounded-lg border border-red-200 px-4 py-2 text-sm font-semibold text-red-600 dark:border-red-700 dark:text-red-400">
              Remove
            </button>
            <button id="clear-selection-button" type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 dark:border-gray-600 dark:text-gray-300">
              Clear all
            </button>
            <button id="continue-button" type="button" disabled class="ml-auto rounded-lg bg-orange-500 px-4 py-2 text-sm font-semibold text-white disabled:bg-gray-300 disabled:text-gray-500 hover:bg-orange-400 transition dark:hover:bg-orange-600">
              <?= $canCheckout ? "Continue" : "Log in to reserve" ?>
            </button>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>
<script id="wall-data" type="application/json"><?= safe_json($wallPixels) ?></script>
<script id="grid-auth" type="application/json"><?= safe_json([
  "loggedIn" => $canCheckout,
  "nextUrl" => "recap.php",
  "loginUrl" => "login.php?next=" . urlencode("recap.php"),
  "reservationMinutes" => $reserveMinutes,
]) ?></script>
<script type="module" src="assets/js/grid-page.js"></script>
<script>
(function () {
  var btn = document.getElementById("leaderboard-button");
  var panel = document.getElementById("leaderboard-panel");
  var close = document.getElementById("leaderboard-close-btn");

  function openPanel() { panel.style.display = "flex"; }
  function closePanel() { panel.style.display = "none"; }

  btn.addEventListener("click", function () {
    panel.style.display === "flex" ? closePanel() : openPanel();
  });
  close.addEventListener("click", closePanel);
})();
</script>
