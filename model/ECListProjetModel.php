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

function get_projets(string $search=''): array
{
    $bdd = get_bdd();
    $idUtil = (int) ($_SESSION['user_id'] ?? 0);

    $sql = "SELECT DISTINCT p.*
            FROM projet AS p
            INNER JOIN referencer AS r ON r.id_proj = p.id_proj
            WHERE r.id_util = :id_util";

    $params = [
        ':id_util' => $idUtil
    ];

    // Si la barre de recherche n'est pas vide, on ajoute le filtre LIKE
    if ($search !== '') {
        $sql .= " AND (p.nom_proj LIKE :search OR p.desc_proj LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }

    $stmt = $bdd->prepare($sql);
    $stmt->execute($params);
    $result= $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}