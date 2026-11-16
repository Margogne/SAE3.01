<?php

function get_bdd()
{
    $serveur = "localhost";
    $base = "lab_project_grp04";
    $user = "root";
    $password = "";

    $dsn = "mysql:host=$serveur;dbname=$base;charset=utf8mb4";
    $pdo =  new PDO($dsn, $user, $password);

    return $pdo;
}

function get_part_name($id)
{
    $bdd = get_bdd();
    $query = $bdd->query('SELECT nom_util FROM utilisateur WHERE id = ' . $id);
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function check_login($nom, $prenom)
{
    $bdd = get_bdd();
    // On prépare la requête pour chercher l'utilisateur par son nom et son prénom
    // Remplace 'nom' et 'prenom' par les noms exacts de tes colonnes dans la table SQL si besoin
    $query = $bdd->prepare('SELECT * FROM utilisateur WHERE nom_util = ? AND prenom_util = ?');
    $query->execute([$nom, $prenom]);
    
    // Retourne les informations de l'utilisateur si trouvé, ou false sinon
    return $query->fetch(PDO::FETCH_ASSOC);
}