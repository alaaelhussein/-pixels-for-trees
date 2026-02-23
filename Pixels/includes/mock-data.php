<?php
$mockMessages = [
    "Pour un futur plus vert",
    "Ensemble, plantons l'espoir",
    "Chaque arbre compte",
    "Pour mes enfants",
    "La nature nous sauvera",
];

$mockUsernames = ["Marie L.", "Jean D.", "Sophie M.", "Lucas B.", "Emma R.", "Thomas V."];

$currentUser = [
    "name" => "Sophie",
    "totalPixels" => 85,
    "totalAmount" => 425,
    "totalTrees" => 85,
];

$mockContributions = [
    [
        "id" => "1",
        "date" => "2026-02-10",
        "pixels" => 25,
        "amount" => 125,
        "status" => "Confirmé via Every.org",
    ],
    [
        "id" => "2",
        "date" => "2026-02-05",
        "pixels" => 10,
        "amount" => 50,
        "status" => "Confirmé via Every.org",
    ],
    [
        "id" => "3",
        "date" => "2026-01-28",
        "pixels" => 50,
        "amount" => 250,
        "status" => "Confirmé via Every.org",
    ],
];

$mockDonations = [
    [
        "id" => "1",
        "date" => "2026-02-12 14:30",
        "username" => "Marie L.",
        "amount" => 75,
        "pixels" => 15,
        "webhookStatus" => "Validé",
    ],
    [
        "id" => "2",
        "date" => "2026-02-12 12:15",
        "username" => "Jean D.",
        "amount" => 100,
        "pixels" => 20,
        "webhookStatus" => "Validé",
    ],
    [
        "id" => "3",
        "date" => "2026-02-11 18:45",
        "username" => "Sophie M.",
        "amount" => 250,
        "pixels" => 50,
        "webhookStatus" => "Validé",
    ],
    [
        "id" => "4",
        "date" => "2026-02-11 16:20",
        "username" => "Lucas B.",
        "amount" => 50,
        "pixels" => 10,
        "webhookStatus" => "Validé",
    ],
    [
        "id" => "5",
        "date" => "2026-02-11 10:00",
        "username" => "Emma R.",
        "amount" => 150,
        "pixels" => 30,
        "webhookStatus" => "En attente",
    ],
];
