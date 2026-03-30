const darkModeKey = "pixels.theme.mode";
const HTML = document.documentElement;
const DARK_CLASS = "dark";

function isDarkMode() {
  const saved = localStorage.getItem(darkModeKey);

  if (saved !== null) {
    return saved === "dark";
  }

  return window.matchMedia("(prefers-color-scheme: dark)").matches;
}

function applyTheme(dark) {
  if (dark) {
    HTML.classList.add(DARK_CLASS);
    localStorage.setItem(darkModeKey, "dark");
  } else {
    HTML.classList.remove(DARK_CLASS);
    localStorage.setItem(darkModeKey, "light");
  }

  updateToggleIcon();
}

function updateToggleIcon() {
  const sunIcon = document.querySelector("#theme-icon-sun");
  const moonIcon = document.querySelector("#theme-icon-moon");

  if (!sunIcon || !moonIcon) {
    return;
  }

  const isDark = HTML.classList.contains(DARK_CLASS);
  sunIcon.classList.toggle("hidden", isDark);
  moonIcon.classList.toggle("hidden", !isDark);
}

function initTheme() {
  const dark = isDarkMode();
  applyTheme(dark);

  const toggle = document.querySelector("#theme-toggle");

  if (!toggle) {
    return;
  }

  toggle.addEventListener("click", () => {
    const currentlyDark = HTML.classList.contains(DARK_CLASS);
    applyTheme(!currentlyDark);
  });

  // only update if the user has not set a preference
  window
    .matchMedia("(prefers-color-scheme: dark)")
    .addEventListener("change", (event) => {
      if (localStorage.getItem(darkModeKey) === null) {
        applyTheme(event.matches);
      }
    });
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initTheme);
} else {
  initTheme();
}
