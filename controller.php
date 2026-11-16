<?php

// 1. Correction du chemin avec __DIR__ et suppression du slash de début
require_once __DIR__ . '/model/model.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // TODO (Prochaine étape) : Récupérer les données ($_POST['name'], $_POST['prenom']) 
    // et interroger le modèle pour vérifier l'existence en base de données.

    // Pour l'instant : redirection directe vers la page d'accueil/partenaire
    header("Location: views/PartAcc.html");
    exit();
    
} else {
    // Si on accède à controller.php directement sans soumettre le formulaire, on renvoie vers le formulaire
    header("Location: views/ID.php");
    exit();
}

?>