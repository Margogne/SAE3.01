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
    $query = $bdd->query('SELECT * FROM experimentation WHERE id_exp=\''. $id .'\'');
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function get_materiel_from_exp($id){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM ressource JOIN contenir on contenir.id_ress=ressource.id_ress JOIN session ON session.id_sess=contenir.id_sess '.
    'WHERE id_exp=\''. $id .'\' AND nom_ress LIKE \'Matériel%\'');
    $result = $query->fetchALL(PDO::FETCH_ASSOC);
    return $result;
}
function get_salle_from_exp($id){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM ressource JOIN contenir on contenir.id_ress=ressource.id_ress JOIN session ON session.id_sess=contenir.id_sess '.
    'WHERE id_exp=\''. $id .'\' AND nom_ress LIKE \'Salle%\'');
    $result = $query->fetchALL(PDO::FETCH_ASSOC);
    return $result;
}

function get_nb_participants(){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM utilisateur as u
    JOIN travailler AS t on t.id_util=u.id_util JOIN experimentation as e ON e.id_exp=t.id_exp JOIN projet as p ON p.id_proj=e.id_proj JOIN referencer as r ON r.id_proj=p.id_proj
    WHERE e.id_exp = '.$_GET['id_exp'] .' AND u.type_util=\'externe\'');
    //$query = $bdd->query('SELECT * FROM ressource');
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return count($result);
}

function get_lab_from_exp($id){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM experimentation as e 
    JOIN projet as p on p.id_proj=e.id_proj JOIN laboratoire as l on l.id_lab=p.id_lab WHERE e.id_exp = \''.$_GET['id_exp'].'\'');
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function get_incidents_from_exp($id): array{
    $bdd = get_bdd();
    $sql = "SELECT * FROM incident AS i INNER JOIN session AS s ON s.id_sess = i.id_sess WHERE s.id_exp = :id_exp";

    $stmt = $bdd->prepare($sql);
    $stmt->execute([ ':id_exp' => $id ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_documents_from_exp($id): array{
    $bdd = get_bdd();
    $sql = "SELECT * FROM document WHERE document.id_exp = :id_exp";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([ ':id_exp' => $id ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_projet_from_exp($id){
    $bdd = get_bdd();
    $sql = "SELECT * FROM experimentation JOIN projet on projet.id_proj=experimentation.id_proj WHERE experimentation.id_exp = :id_exp";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([ ':id_exp' => $id ]);
    return $stmt->fetch(PDO::FETCH_ASSOC);

}