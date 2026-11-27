<?php 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ID.php");
    exit();
}

$userNom = $_SESSION['user_nom'] ?? 'Utilisateur';
$userPrenom = $_SESSION['user_prenom'] ?? '';
$userRole = $_SESSION['user_role'] ?? 'Enseignant chercheur';
$initials = strtoupper(mb_substr($userPrenom, 0, 1) . mb_substr($userNom, 0, 1)) ?: 'U';

include("../model/ECFicheExpModel.php");

$exp=get_experimentation($_GET['id_exp']);
?>