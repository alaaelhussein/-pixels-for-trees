<?php
require_once __DIR__ . "/includes/app/boot.php";

$pageTitle = "Pixels for Trees";
$stats = wall_stats();
$wallPixels = grid_pixels();

require_once __DIR__ . "/includes/header.php";
?>
<div>

  <!-- Section hero -->
  <div style="background:#16a34a;display:flex;justify-content:center;padding:2rem 1rem;">
    <div style="position:relative;display:inline-block;line-height:0;">
      <img
        src="assets/media/header.png"
        alt="Pixels for Trees"
        style="display:block;max-width:50vw;width:auto;height:auto;image-rendering:auto;"
      />
      <a href="grid.php" style="position:absolute;bottom:1rem;left:50%;transform:translateX(-50%);white-space:nowrap;" class="inline-block bg-orange-500 hover:bg-orange-400 text-white px-8 py-3 rounded-full text-base font-bold transition shadow-xl">
        Open the grid →
      </a>
    </div>
  </div>

  <div class="bg-white border-b border-gray-100 py-6 px-4 text-center">
    <p class="text-gray-500 text-sm max-w-xl mx-auto">
      Choose a color, reserve a pixel, donate through Every.org — and your pixel lights up the wall permanently.
    </p>
  </div>

  <section class="py-12 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
      <h2 class="text-center text-3xl font-bold text-gray-900 mb-8">
        The 1000 × 1000 grid
      </h2>
      <div class="flex flex-col lg:flex-row gap-12 items-center justify-center">
        <div class="flex-shrink-0">
          <div id="landing-grid"></div>
          <div class="mt-4 flex gap-6 text-sm">
            <div class="flex items-center gap-2">
              <div class="w-4 h-4 bg-white border border-gray-300"></div>
              <span>Free</span>
            </div>
            <div class="flex items-center gap-2">
              <div class="w-4 h-4 bg-slate-400"></div>
              <span>Reserved</span>
            </div>
          </div>
        </div>
        <div class="max-w-md space-y-4 text-gray-700">
          <p class="text-lg">
            The wall loads active reservations and confirmed cells.
          </p>
          <p>
            A selection stays pending until the donation comes back.
            The final lock happens at confirmation time.
          </p>
          <p>
            Payments go through Every.org.
            The impact is visible on the wall.
          </p>
        </div>
      </div>
    </div>
  </section>

  <section class="py-16 px-4 bg-green-600 text-white">
    <div class="max-w-7xl mx-auto grid md:grid-cols-3 gap-8 text-center">
      <div>

        <p class="text-4xl font-bold mb-2"><?= $stats["trees"] ?></p>
        <p class="text-green-100">Trees supported</p>
      </div>
      <div>

        <p class="text-4xl font-bold mb-2"><?= $stats["pixels"] ?></p>
        <p class="text-green-100">Confirmed pixels</p>
      </div>
      <div>

        <p class="text-4xl font-bold mb-2"><?= money($stats["amount"]) ?></p>
        <p class="text-green-100">Confirmed donations</p>
      </div>
    </div>
  </section>
</div>
<script id="wall-data" type="application/json"><?= safe_json($wallPixels) ?></script>
<script type="module" src="assets/js/landing-page.js"></script>
<?php require_once __DIR__ . "/includes/footer.php"; ?>
