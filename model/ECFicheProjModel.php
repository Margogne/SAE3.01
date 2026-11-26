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
function get_documents_from_proj($id): array{
    $bdd = get_bdd();
    $sql = "SELECT * FROM document JOIN experimentation as e on e.id_exp=document.id_exp WHERE e.id_proj = :id_proj";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([ ':id_proj' => $id ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_lab_from_projet($id){
    $bdd = get_bdd();
    $sql = "SELECT * FROM projet as p JOIN laboratoire as l on l.id_lab=p.id_lab WHERE p.id_proj = :id_proj";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([ ':id_proj' => $id ]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_materiels_from_proj($id){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM ressource JOIN contenir on contenir.id_ress=ressource.id_ress JOIN session ON session.id_sess=contenir.id_sess '.
    'JOIN experimentation as e on e.id_exp=session.id_exp '.
    'WHERE e.id_proj=\''. $id .'\' AND nom_ress LIKE \'Matériel%\'');
    $result = $query->fetchALL(PDO::FETCH_ASSOC);
    return $result;
}

function get_salles_from_proj($id){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM ressource JOIN contenir on contenir.id_ress=ressource.id_ress JOIN session ON session.id_sess=contenir.id_sess '.
    'JOIN experimentation as e on e.id_exp=session.id_exp '.
    'WHERE e.id_proj=\''. $id .'\' AND nom_ress LIKE \'Salle%\'');
    $result = $query->fetchALL(PDO::FETCH_ASSOC);
    return $result;
}

function get_EC_from_proj($id){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM utilisateur as u JOIN referencer as r on r.id_util=u.id_util WHERE r.id_proj=\''.$id.'\'');
    //$query = $bdd->query('SELECT * FROM ressource');
    $result = $query->fetch(PDO::FETCH_ASSOC);

    return $result;
}

?>