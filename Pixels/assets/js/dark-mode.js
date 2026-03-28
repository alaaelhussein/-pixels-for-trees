// Gestion du mode sombre avec localStorage et détection système

const darkModeKey = "pixels.theme.mode";
const HTML = document.documentElement;
const DARK_CLASS = "dark";

function isDarkMode() {
  const saved = localStorage.getItem(darkModeKey);

  if (saved !== null) {
    return saved === "dark";
  }

  // Détection système si rien n'est sauvé
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
  const toggle = document.querySelector("#theme-toggle");

  if (!toggle) {
    return;
  }

  const isDark = HTML.classList.contains(DARK_CLASS);
  toggle.textContent = isDark ? "🌙" : "☀️";
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

  // Écouter les changements de préférence système
  window
    .matchMedia("(prefers-color-scheme: dark)")
    .addEventListener("change", (event) => {
      // Ne changer automatiquement que si l'utilisateur n'a pas défini sa préférence
      if (localStorage.getItem(darkModeKey) === null) {
        applyTheme(event.matches);
      }
    });
}

// Initialiser dès le chargement du DOM
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initTheme);
} else {
  initTheme();
}
