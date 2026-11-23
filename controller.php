<?php
session_start();

require_once __DIR__ . '/model/model.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom = trim($_POST['name'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');

    $utilisateur = check_login($nom, $prenom);

    if ($utilisateur) {

        $_SESSION['user_id'] = $utilisateur['id_util'];
        $_SESSION['user_nom'] = $utilisateur['nom_util'];
        $_SESSION['user_prenom'] = $utilisateur['prenom_util'];
        $_SESSION['user_role'] = $utilisateur['type_util'];

        // Redirection vers la page d'accueil sécurisée
        $role = strtolower(trim($utilisateur['type_util'] ?? ''));

        if ($role === 'enseignant chercheur') {
            header("Location: views/ECDashboard.php");
            exit();
        } elseif ($role === 'externe') {
            header("Location: views/PartAcc.php");
            exit();
        } else {
            header("Location: views/ID.php?erreur=1");
            exit();
        }
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

/*
if (!isset($_SESSION['user_id'])) {
    // S'il n'est pas connecté, on le redirige vers la page d'identification
    header("Location: views/ID.php");
    exit();
}

// Récupération de l'ID de l'utilisateur connecté
$current_user_id = $_SESSION['user_id'];
*/
?>