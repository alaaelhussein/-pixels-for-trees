<?php
require_once __DIR__ . "/includes/app/boot.php";
require_once __DIR__ . "/includes/app/http.php";

$next = safe_next_path($_GET["next"] ?? "grid.php");

if (current_user()) {
  go($next);
}

$pageTitle = "Log in";
require_once __DIR__ . "/includes/header.php";
?>
<div class="min-h-screen bg-gray-50 py-12 px-4">
  <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold mb-6">Log in</h1>
    <form method="post" action="actions/login.php" class="space-y-4">
      <input type="hidden" name="csrf" value="<?= csrf_token() ?>" />
      <input type="hidden" name="next" value="<?= htmlspecialchars($next) ?>" />
      <input name="email" type="email" placeholder="Email"
        class="w-full border rounded-lg px-4 py-3" required />
      <input name="password" type="password"
        placeholder="Password"
        class="w-full border rounded-lg px-4 py-3" required />
      <button class="w-full bg-orange-500 text-white rounded-lg py-3">
        Log in
      </button>
    </form>
    <p class="text-sm mt-4 text-center">
      <a href="register.php?next=<?= urlencode($next) ?>" class="text-blue-600">Create an account</a>
    </p>
  </div>
</div>
<?php require_once __DIR__ . "/includes/footer.php"; ?>
