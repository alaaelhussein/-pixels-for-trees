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

require_once __DIR__ . "/includes/header.php";
?>
<div class="bg-gray-950" style="height: calc(100vh - 73px);">
  <main class="relative h-full overflow-hidden">
    <div id="grid-container" class="absolute inset-0"></div>

    <div class="absolute left-3 top-3 z-30 flex flex-col gap-2">
      <button id="info-button" type="button" title="Info" class="h-10 w-10 rounded-full bg-white/95 text-sm font-bold text-gray-900 shadow-lg">
        i
      </button>
      <button id="zoom-in-btn" type="button" title="Zoom in" class="h-10 w-10 rounded-full bg-white/95 text-xl text-gray-900 shadow-lg">
        +
      </button>
      <button id="zoom-out-btn" type="button" title="Zoom out" class="h-10 w-10 rounded-full bg-white/95 text-xl text-gray-900 shadow-lg">
        −
      </button>
      <button id="reset-view-btn" type="button" title="Reset view" class="h-10 w-10 rounded-full bg-white/95 text-sm font-semibold text-gray-900 shadow-lg">
        ↺
      </button>
      <div id="grid-info-panel" class="hidden mt-1 w-72 rounded-2xl bg-white/95 p-4 text-sm text-gray-700 shadow-2xl">
        <h1 class="text-lg font-semibold text-gray-900">Choose your pixels</h1>
        <p class="mt-2">
          The view starts at the top-left corner.
          It opens at about 100 × 50 cells.
        </p>
        <p class="mt-2">
          Click a free cell to open the drawer.
          Reserved cells are locked for other people.
          You can zoom out to see more of the wall.
        </p>
        <p class="mt-2 text-blue-700">
          <?= $canCheckout
              ? "Reservation starts when you continue and lasts {$reserveMinutes} minutes."
              : "Guests can test colors first. Reservation starts after log in." ?>
        </p>
        <div class="mt-3 flex flex-wrap gap-3 text-xs font-medium text-gray-600">
          <?php foreach ($gridLegend as $item): ?>
            <div class="flex items-center gap-2">
              <div class="<?= join_classes($item['box']) ?>"></div>
              <span><?= $item["label"] ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="absolute right-3 top-3 z-40 flex flex-col items-end gap-3">
      <div class="flex flex-col items-end gap-2">
        <?php if ($user): ?>
          <a href="logout.php" class="rounded-xl bg-white/95 px-4 py-2 text-sm font-semibold text-gray-900 shadow-lg">
            Log out
          </a>
        <?php else: ?>
          <a href="<?= htmlspecialchars($loginNext) ?>" class="rounded-xl bg-orange-500 px-4 py-2 text-sm font-semibold text-white shadow-lg">
            Log in
          </a>
        <?php endif; ?>
      </div>

      <div class="rounded-2xl bg-white/95 p-3 shadow-xl">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
          Jump to cell
        </p>
        <div class="mt-2 flex items-center gap-2">
          <input id="jump-x" type="number" min="0" max="999" placeholder="X" class="w-20 rounded-lg border border-gray-200 px-3 py-2 text-sm" />
          <input id="jump-y" type="number" min="0" max="999" placeholder="Y" class="w-20 rounded-lg border border-gray-200 px-3 py-2 text-sm" />
          <button id="jump-button" type="button" class="rounded-lg bg-gray-900 px-3 py-2 text-sm font-semibold text-white">
            Go
          </button>
        </div>
      </div>
    </div>

    <div id="cell-drawer" class="hidden absolute bottom-0 left-0 z-50 w-full px-3 pb-0 sm:left-1/2 sm:max-w-md sm:-translate-x-1/2 md:max-w-lg">
      <div class="w-full rounded-t-2xl border border-gray-200 bg-white shadow-2xl sm:mb-3 sm:rounded-2xl">
        <div class="flex items-center justify-between gap-3 border-b border-gray-200 px-4 py-3">
          <div class="min-w-0">
            <div class="flex items-center gap-2">
              <span id="drawer-status" class="rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-700">
                Selected
              </span>
              <span id="active-cell" class="text-sm font-semibold text-gray-900">
                Cell —
              </span>
            </div>
            <p id="drawer-meta" class="mt-1 text-xs text-gray-500"></p>
          </div>
          <button id="close-drawer-button" type="button" class="h-8 w-8 rounded-full border border-gray-200 text-sm text-gray-700">
            ×
          </button>
        </div>

        <div class="px-4 py-3">
          <div class="flex items-center gap-3">
            <div id="drawer-color-preview" class="h-12 w-12 rounded-xl border border-gray-200 bg-orange-400"></div>
            <div>
              <p id="drawer-note" class="text-sm text-gray-700"></p>
              <p id="drawer-owner" class="mt-1 text-xs text-gray-500"></p>
            </div>
          </div>

          <div id="drawer-editor" class="mt-4">
            <p class="mb-2 text-xs font-medium uppercase tracking-wide text-gray-500">Pick a color</p>
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
            </div>
            <input id="pixel-color-text" type="text" value="#FB923C" maxlength="7" placeholder="#FB923C" class="w-full rounded-lg border border-gray-200 px-3 py-2 font-mono text-sm uppercase" />
            <label for="pixel-message" class="mt-4 block text-sm font-medium text-gray-700">
              Public message
            </label>
            <textarea id="pixel-message" class="mt-2 w-full rounded-lg border border-gray-200 px-4 py-3" rows="3" maxlength="120"></textarea>
          </div>

          <div id="drawer-locked" class="hidden mt-4 rounded-xl bg-slate-50 p-4 text-sm text-slate-700">
            <p id="drawer-locked-text"></p>
          </div>

          <div class="mt-4 grid grid-cols-3 gap-3 rounded-xl bg-gray-50 p-4 text-sm text-gray-700">
            <div>
              <p class="text-xs uppercase tracking-wide text-gray-500">Selected</p>
              <p id="selected-count" class="mt-1 text-lg font-semibold text-gray-900">0</p>
            </div>
            <div>
              <p class="text-xs uppercase tracking-wide text-gray-500">Amount</p>
              <p id="total-amount" class="mt-1 text-lg font-semibold text-gray-900">0 $</p>
            </div>
            <div>
              <p class="text-xs uppercase tracking-wide text-gray-500">Trees</p>
              <p id="total-trees" class="mt-1 text-lg font-semibold text-gray-900">0</p>
            </div>
          </div>

          <div class="mt-4 flex flex-wrap gap-2">
            <button id="remove-active-button" type="button" class="rounded-lg border border-red-200 px-4 py-2 text-sm font-semibold text-red-600">
              Remove
            </button>
            <button id="clear-selection-button" type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700">
              Clear all
            </button>
            <button id="continue-button" type="button" disabled class="ml-auto rounded-lg bg-orange-500 px-4 py-2 text-sm font-semibold text-white disabled:bg-gray-300 disabled:text-gray-500">
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
<?php require_once __DIR__ . "/includes/footer.php"; ?>
