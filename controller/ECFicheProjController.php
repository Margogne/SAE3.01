<?php

include("../model/ECFicheProjModel.php");
session_start();
$id=$_GET["id_proj"];
$projet=get_projet($id);
$exp=get_experimentations_by_projet($id);

if (!isset($_SESSION['user_id'])) {
    header("Location: ID.php");
    exit();
}

$userNom = $_SESSION['user_nom'] ?? 'Utilisateur';
$userPrenom = $_SESSION['user_prenom'] ?? '';
$userRole = $_SESSION['user_role'] ?? 'Enseignant chercheur';
$initials = strtoupper(mb_substr($userPrenom, 0, 1) . mb_substr($userNom, 0, 1)) ?: 'U';




?>