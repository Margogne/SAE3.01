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

function get_experimentations(){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM experimentation ORDER BY datefin_exp DESC LIMIT 5') ;
    //$query = $bdd->query('SELECT * FROM ressource');
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}


?>