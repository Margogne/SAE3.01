<?php
include("../model/ECModel.php") ;
session_start();

if(isset($_POST["titre_exp"]) && isset( $_POST["desc_exp"]) && isset($_POST["id_proj"])){
    insert_exp();
}
if (isset($_POST["nom_proj"]) && isset($_POST["desc_proj"]) && isset($_POST["datefin_proj"]) && isset($_POST["id_lab"])) {
    insert_proj();
}
if (isset($_POST['titre_incid'], $_POST['desc_incid'], $_POST['id_sess'])) {
    insert_incident();
}
if (isset($_POST['desc_rap'])) {
    insert_rapport();
}
$userNom = $_SESSION['user_nom'] ?? 'Utilisateur';
$userPrenom = $_SESSION['user_prenom'] ?? '';
$userRole = $_SESSION['user_role'] ?? 'Enseignant chercheur';
$initials = strtoupper(mb_substr($userPrenom, 0, 1) . mb_substr($userNom, 0, 1)) ?: 'U';
?>