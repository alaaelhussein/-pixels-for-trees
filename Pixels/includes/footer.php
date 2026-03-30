<?php
require_once __DIR__ . "/site-data.php";
require_once __DIR__ . "/site-tools.php";
?>
  <footer
    class="text-gray-300"
    style="
      position:relative;
      background-image:url('assets/media/footer.png');
      background-repeat:repeat-x;
      background-position:left top;
      background-size:auto;
    "
  >
    <div style="position:absolute;inset:0;background:rgba(0,0,0,0.65);pointer-events:none;"></div>
    <div
      class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12"
      style="position:relative;z-index:1;"
    >
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center" style="grid-template-columns:1fr 1fr;">
        <div class="flex flex-col items-center text-center">
          <img
            src="assets/media/navbar-logo.png"
            alt="Pixels for Trees"
            style="display:block;height:48px;width:auto;opacity:.9;"
          />
          <p class="text-sm mt-4">
            Light up a pixel, fund a tree,
            track your impact.
          </p>
        </div>

        <div class="flex flex-col items-center text-center">
          <h3 class="text-white font-semibold mb-4">
            Partners
          </h3>
          <div class="space-y-2 text-sm">
            <?php foreach ($footerPartners as $item): ?>
              <div class="flex items-center gap-2">
                <div
                  class="<?= join_classes([
                      'w-6',
                      'h-6',
                      $item['dot'],
                      'rounded',
                  ]) ?>"
                ></div>
                <span><?= $item["label"] ?></span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <div
        class="border-t border-gray-800 mt-8 pt-8
        text-sm text-center"
      >
        <p>
          &copy; 2026 Pixels for Trees.
          Built for visible impact.
        </p>
      </div>
    </div>
  </footer>
</body>
</html>
