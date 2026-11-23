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

function get_projet($id){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM projet WHERE id_proj=\''. $id .'\'');
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result;

}
function get_experimentations_by_projet(int $idProj): array
{
    $bdd = get_bdd();
    $sql = "SELECT *
            FROM experimentation
            WHERE id_proj = :id_proj
            ORDER BY datedeb_exp DESC";

    $stmt = $bdd->prepare($sql);
    $stmt->execute([
        ':id_proj' => $idProj
    ]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}


?>