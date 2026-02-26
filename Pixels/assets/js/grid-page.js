

//  IMPORTS
// Import des fonctions utilistaires pour la grille de pixels
import { createPixelGrid, generateMockPixels } from "./pixel-grid.js";

// ===== INITIALISATION =====
// Attendre que le DOM soit complètement chargé avant d'initialiser
document.addEventListener("DOMContentLoaded", () => {
  // ===== SÉLECTION DES ÉLÉMENTS DOM =====
  // Container principal pour la grille interactive
  const container = document.querySelector("#grid-container");
  // Affichage du nombre de pixels sélectionnés
  const selectedCountEl = document.querySelector("#selected-count");
  // Affichage du montant total (en €)
  const totalAmountEl = document.querySelector("#total-amount");
  // Affichage du nombre d'arbres qui seront plantés
  const totalTreesEl = document.querySelector("#total-trees");
  // Bouton pour passer au récapitulatif
  const continueButton = document.querySelector("#continue-button");
  // Message d'indice si aucun pixel n'est sélectionné
  const emptyHint = document.querySelector("#empty-hint");

  // Validation : vérifier que tous les éléments DOM existent
  if (!container || !selectedCountEl || !totalAmountEl || !totalTreesEl || !continueButton) return;

  //  VARIABLES D'ÉTAT 
  // Tableau stockant les pixels actuellement sélectionnés par l'utilisateur
  let selectedPixels = [];

  // ===== CONFIGURATION DE LA GRILLE =====
  // Taille de la grille (100x100 pixels)
  const gridSize = 100;
  // Génération de 500 pixels fictifs pour la démonstration
  const pixels = generateMockPixels(500, gridSize);
  // Initialisation de la grille interactive avec configuration personnalisée
  const grid = createPixelGrid(container, {
    // Liste des pixels à afficher
    pixels,
    // Dimensions de la grille
    gridSize,
    // Activer le mode interactif (clic pour sélectionner)
    interactive: true,
    // Callback : exécuté chaque fois que la sélection change
    onSelectionChange: (selection) => {
      // Mise à jour du tableau des pixels sélectionnés
      selectedPixels = selection;
      // Calcul du montant total : 1 pixel = 5 € (tarif fixe)
      const totalAmount = selectedPixels.length * 5;

      // Mise à jour des compteurs affichés
      // 1. Nombre de pixels sélectionnés
      selectedCountEl.textContent = String(selectedPixels.length);
      // 2. Montant total en euros
      totalAmountEl.textContent = `${totalAmount} €`;
      // 3. Nombre d'arbres qui seront plantés (= nombre de pixels)
      totalTreesEl.textContent = String(selectedPixels.length);

      // Gestion de l'état du bouton "Continuer"
      // Le bouton est désactivé si aucun pixel n'est sélectionné
      const isDisabled = selectedPixels.length === 0;
      continueButton.disabled = isDisabled;
      // Affichage/masquage du message d'indice si la sélection est vide
      if (emptyHint) {
        emptyHint.classList.toggle("hidden", !isDisabled);
      }
    },
  });

  // ===== GESTION DES ÉVÉNEMENTS =====
  // Bouton pour réinitialiser la vue de la grille
  const resetBtn = document.querySelector("#reset-view-btn");
  if (resetBtn) {
    // Permettre à l'utilisateur de réinitialiser le zoom/pan de la grille
    resetBtn.addEventListener("click", () => grid.resetView());
  }

  // Écouteur du bouton "Continuer" pour passer au récapitulatif
  continueButton.addEventListener("click", () => {
    // Vérification de sécurité : au moins un pixel doit être sélectionné
    if (!selectedPixels.length) return;
    // Sauvegarde de la sélection dans localStorage pour persistence cross-page
    localStorage.setItem("selectedPixels", JSON.stringify(selectedPixels));
    // Redirection vers la page de récapitulatif
    window.location.href = "recap.php";
  });
});


