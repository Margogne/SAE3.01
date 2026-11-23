<?php

if (isset($_POST["nom_proj"]) && isset($_POST["desc_proj"]) && isset($_POST["datefin_proj"]) && isset($_POST["id_lab"])) {
    insert_proj();
}
echo var_dump($_POST);
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

function insert_proj()
{
    $bdd = get_bdd();
    $sql = "INSERT INTO projet (
            nom_proj,
            desc_proj,
            datedeb_proj,
            datefin_proj,
            id_lab
        ) VALUES (
            :nom_proj,
            :desc_proj,
            :datedeb_proj,
            :datefin_proj,
            :id_lab
        )";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([
        ':nom_proj'     => $_POST['nom_proj'] ?? '',
        ':desc_proj'    => $_POST['desc_proj'] ?? '',
        ':datedeb_proj' => (new DateTimeImmutable())->format('Y-m-d'),
        ':datefin_proj' => !empty($_POST['datefin_proj']) ? $_POST['datefin_proj'] : null,
        ':id_lab'       => (int) ($_POST['id_lab'] ?? 0),
    ]);
}