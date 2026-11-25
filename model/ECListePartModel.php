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

function get_participants_from_exp($idExp): array
{

    $bdd = get_bdd();

    $sql = "SELECT u.*, t.num_ordre, r.nom_role
            FROM utilisateur AS u
            INNER JOIN travailler AS t ON t.id_util = u.id_util
            INNER JOIN experimentation AS e ON e.id_exp = t.id_exp
            LEFT JOIN referencer AS r ON r.id_proj = e.id_proj AND r.id_util = u.id_util
            WHERE e.id_exp = :id_exp
              AND u.type_util = 'externe'";

    $stmt = $bdd->prepare($sql);
    $stmt->execute([
        ':id_exp' => $idExp
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
