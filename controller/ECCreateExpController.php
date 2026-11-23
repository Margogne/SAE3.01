<?php
include("../model/ECCreateExpModel.php") ;
session_start();
if(isset($_POST["titre_exp"]) && isset( $_POST["desc_exp"]) && isset($_POST["id_proj"])){
    insert_exp();
}
$userNom = $_SESSION['user_nom'] ?? 'Utilisateur';
$userPrenom = $_SESSION['user_prenom'] ?? '';
$userRole = $_SESSION['user_role'] ?? 'Enseignant chercheur';
$initials = strtoupper(mb_substr($userPrenom, 0, 1) . mb_substr($userNom, 0, 1)) ?: 'U';
?>