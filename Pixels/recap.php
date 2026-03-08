<?php
require_once __DIR__ . "/includes/app/boot.php";
require_once __DIR__ . "/includes/icons.php";

require_user();

$pageTitle = "Summary";

require_once __DIR__ . "/includes/header.php";
?>
<div class="min-h-screen flex flex-col bg-gray-50">
  <main class="flex-1 py-12 px-4">
    <div class="max-w-2xl mx-auto">
      <a href="grid.php" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-6 transition">
        <?= icon_arrow_left("w-5 h-5") ?>
        Back to the grid
      </a>
      <div class="bg-white rounded-lg shadow-md p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">
          Summary of your contribution
        </h1>
        <div class="space-y-4 mb-8">
          <div class="bg-gray-50 rounded-lg p-6 grid grid-cols-2 gap-6">
            <div>
              <p class="text-gray-600 text-sm mb-1">Pixel count</p>
              <p id="pixel-count" class="text-3xl font-bold text-gray-900">0</p>
            </div>
            <div>
              <p class="text-gray-600 text-sm mb-1">Total amount</p>
              <p id="total-amount" class="text-3xl font-bold text-green-600">0 $</p>
            </div>
          </div>
          <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <p class="text-green-800">
              <span id="impact-count" class="font-semibold">0</span>
              trees will be supported.
            </p>
          </div>
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-blue-800 text-sm">
              The next step reserves your pixels for 5 minutes.
            </p>
          </div>
        </div>
        <div class="mb-8 p-4 bg-gray-50 rounded-lg">
          <p class="text-sm text-gray-600 mb-2">Preview:</p>
          <div id="pixel-preview" class="flex gap-1 flex-wrap"></div>
        </div>
        <form method="post" action="actions/create-donation.php" class="space-y-3">
          <input type="hidden" name="csrf" value="<?= csrf_token() ?>" />
          <input type="hidden" id="recap-pixels" name="pixels" value="[]" />
          <input type="hidden" id="recap-message" name="message" value="" />
          <button id="confirm-button" class="w-full bg-orange-500 hover:bg-orange-600 text-white py-4 rounded-lg font-semibold text-lg transition shadow-md hover:shadow-lg">
            Continue to Every.org
          </button>
          <a href="grid.php" class="block w-full text-center text-gray-600 hover:text-gray-900 py-2 transition">
            Edit my selection
          </a>
        </form>
      </div>
    </div>
  </main>
</div>
<script type="module" src="assets/js/recap-page.js"></script>
<?php require_once __DIR__ . "/includes/footer.php"; ?>
