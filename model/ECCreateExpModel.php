<?php

if(isset($_POST["titre_exp"]) && isset( $_POST["desc_exp"]) && isset($_POST["id_proj"])){
    insert_exp();
}
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

function get_projets(){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM projet JOIN referencer on referencer.id_proj=projet.id_proj WHERE referencer.id_util='.$_SESSION['user_id'].' ') ;
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function insert_exp(){
    $bdd = get_bdd();
    $sql = "INSERT INTO experimentation (
            titre_exp,
            desc_exp,
            datefin_exp,
            dur_exp,
            retrib_exp,
            datedeb_exp,
            id_proj
        ) VALUES (
            :titre_exp,
            :desc_exp,
            :datefin_exp,
            :dur_exp,
            :retrib_exp,
            :datedeb_exp,
            :id_proj
        )";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([
    ':titre_exp'   => $_POST['titre_exp'] ?? '',
    ':desc_exp'    => $_POST['desc_exp'] ?? '',
    ':datefin_exp' => $_POST['datefin_exp'] ?? null,
    ':dur_exp'     => $dur_exp = (new DateTimeImmutable($_POST['datedeb_exp']))->diff(new DateTimeImmutable($_POST['datefin_exp']))->m + ((new DateTimeImmutable($_POST['datedeb_exp']))->diff(new DateTimeImmutable($_POST['datefin_exp']))->y * 12).' mois',
    ':retrib_exp'  => $_POST['retrib_exp'] ?? null,
    ':datedeb_exp' => $_POST['datedeb_exp'] ?? null,
    ':id_proj'     =>$_POST['id_proj'] ?? 0 
]);
}

?>