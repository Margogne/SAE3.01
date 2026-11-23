<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ID.php");
    exit();
}
$search = trim($_GET["search"] ?? '');
require ('../model/ECListProjetModel.php');
$userNom = $_SESSION['user_nom'] ?? 'Utilisateur';
$userPrenom = $_SESSION['user_prenom'] ?? '';
$userRole = $_SESSION['user_role'] ?? 'Enseignant chercheur';
$initials = strtoupper(mb_substr($userPrenom, 0, 1) . mb_substr($userNom, 0, 1)) ?: 'U';

$projects = get_projets($search);

//require_once '../model/model.php';

/*$pageData = init_part_acc_page($_GET['search'] ?? '', $_SESSION);
if ($pageData === null) {
    header('Location: ID.php');
    exit();
}*/

//extract($pageData);






?>