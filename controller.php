<?php
session_start();

// 1. Correction du chemin avec __DIR__ et suppression du slash de début
require_once __DIR__ . '/model/model.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $nom = trim($_POST['name'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');

    $utilisateur = check_login($nom, $prenom);

    if ($utilisateur) {
        // Succès : l'utilisateur existe en BDD
        // (Optionnel mais recommandé) On peut démarrer une session pour garder ses infos en mémoire
        $_SESSION['user_id'] = $utilisateur['id']; // Remplace 'id' par le nom exact de ta colonne ID en SQL
        $_SESSION['user_nom'] = $utilisateur['nom'];
        $_SESSION['user_prenom'] = $utilisateur['prenom'];

        // Redirection vers la page d'accueil sécurisée
        header("Location: views/PartAcc.php");
        exit();
    } else {
        // Échec : aucun utilisateur trouvé avec ce nom et prénom
        // Pour l'instant, on le renvoie vers la page d'identification
        header("Location: views/ID.php?erreur=1");
        exit();
    }
    
} else {
    // Si on accède à controller.php directement sans soumettre le formulaire, on renvoie vers le formulaire
    header("Location: views/ID.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    // S'il n'est pas connecté, on le redirige vers la page d'identification
    header("Location: views/ID.php");
    exit();
}

// Récupération de l'ID de l'utilisateur connecté
$current_user_id = $_SESSION['user_id'];

?> 