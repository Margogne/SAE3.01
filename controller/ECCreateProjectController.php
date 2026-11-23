<?php
include '../model/ECCreateProjModel.php';
session_start();

$userNom = $_SESSION['user_nom'] ?? 'Utilisateur';
$userPrenom = $_SESSION['user_prenom'] ?? '';
$userRole = $_SESSION['user_role'] ?? 'Enseignant chercheur';
$initials = strtoupper(mb_substr($userPrenom, 0, 1) . mb_substr($userNom, 0, 1)) ?: 'U';



if (isset($_POST["nom_proj"]) && isset($_POST["desc_proj"]) && isset($_POST["datefin_proj"]) && isset($_POST["id_lab"])) {
    insert_proj();
}
?>