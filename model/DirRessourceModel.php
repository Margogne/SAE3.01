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

function get_equipements_materiels(){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM ressource WHERE desc_ress LIKE \'Matériel%\'');
    //$query = $bdd->query('SELECT * FROM ressource');
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function get_salles(){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM ressource WHERE desc_ress LIKE \'Salle%\'');
    //$query = $bdd->query('SELECT * FROM ressource');
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

?>