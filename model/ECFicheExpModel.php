<?php



function get_bdd()
{
    $serveur = "localhost";
    $base = "lab_project_grp04";
    $user = "root";
    $password = "";

    $dsn = "mysql:host=$serveur;dbname=$base;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $password);

    return $pdo;
}

function get_labs()
{
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM laboratoire');
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function get_experimentation($id){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM experimentation WHERE id_exp='. $id .'\'');
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}