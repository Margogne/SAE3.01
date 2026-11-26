<?php
/*-----------------------------------------
            Base
-----------------------------------------*/
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
/*-----------------------------------------
            Get
-----------------------------------------*/

/* #Projet */
function get_projet($id){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM projet WHERE id_proj=\''. $id .'\'');
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function get_projets(){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM projet') ;
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}
function get_projets_with_search(string $search=''): array
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
    if ($search !== '') {
        $sql .= " AND (p.nom_proj LIKE :search OR p.desc_proj LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }
    $stmt = $bdd->prepare($sql);
    $stmt->execute($params);
    $result= $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}
function get_projets_from_util($id) : array{
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM projet JOIN referencer on referencer.id_proj=projet.id_proj WHERE id_util = \''.$id.'\'') ;
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}
function get_projet_from_exp($id){
    $bdd = get_bdd();
    $sql = "SELECT * FROM experimentation JOIN projet on projet.id_proj=experimentation.id_proj WHERE experimentation.id_exp = :id_exp";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([ ':id_exp' => $id ]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/* #Laboratoire */
function get_labs()
{
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM laboratoire');
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}
function get_lab_from_exp($id){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM experimentation as e 
    JOIN projet as p on p.id_proj=e.id_proj JOIN laboratoire as l on l.id_lab=p.id_lab WHERE e.id_exp = \''.$_GET['id_exp'].'\'');
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result;
}
function get_lab_from_projet($id){
    $bdd = get_bdd();
    $sql = "SELECT * FROM projet as p JOIN laboratoire as l on l.id_lab=p.id_lab WHERE p.id_proj = :id_proj";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([ ':id_proj' => $id ]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/* #Expérimentation */
function get_experimentation($id){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM experimentation WHERE id_exp=\''. $id .'\'');
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result;
}
function get_experimentations_from_util($id){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM experimentation JOIN travailler on experimentation.id_exp=travailler.id_exp 
    WHERE id_util = '.$id.' ORDER BY datefin_exp DESC LIMIT 5') ;
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}
function get_experimentations_from_projet($id): array
{
    $bdd = get_bdd();
    $sql = "SELECT *
            FROM experimentation
            WHERE id_proj = :id_proj
            ORDER BY datedeb_exp DESC";

    $stmt = $bdd->prepare($sql);
    $stmt->execute([
        ':id_proj' => $id
    ]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function get_prochaine_echeance_exp_from_util($id): string
{
    $bdd = get_bdd();
    $sql = "SELECT e.titre_exp, e.datefin_exp
            FROM experimentation AS e
            INNER JOIN travailler AS t ON t.id_exp = e.id_exp
            WHERE t.id_util = :id_util
              AND e.datefin_exp >= CURDATE()
            ORDER BY e.datefin_exp ASC
            LIMIT 1";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([
        ':id_util' => (int) ($id ?? 0)
    ]);
    $prochaine = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$prochaine || empty($prochaine['datefin_exp'])) {
        return 'Aucune échéance à venir';
    }
    $date = new DateTimeImmutable($prochaine['datefin_exp']);
    return $date->format('d/m/Y');
}

/* #Ressource */
function get_materiels(){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM ressource WHERE desc_ress LIKE \'Matériel%\'');
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function get_materiel_from_exp($id){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM ressource JOIN contenir on contenir.id_ress=ressource.id_ress JOIN session ON session.id_sess=contenir.id_sess '.
    'WHERE id_exp=\''. $id .'\' AND nom_ress LIKE \'Matériel%\'');
    $result = $query->fetchALL(PDO::FETCH_ASSOC);
    return $result;
}
function get_materiels_from_proj($id){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM ressource JOIN contenir on contenir.id_ress=ressource.id_ress JOIN session ON session.id_sess=contenir.id_sess '.
    'JOIN experimentation as e on e.id_exp=session.id_exp '.
    'WHERE e.id_proj=\''. $id .'\' AND nom_ress LIKE \'Matériel%\'');
    $result = $query->fetchALL(PDO::FETCH_ASSOC);
    return $result;
}

function get_salles(){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM ressource WHERE desc_ress LIKE \'Salle%\'');
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}
function get_salle_from_exp($id){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM ressource JOIN contenir on contenir.id_ress=ressource.id_ress JOIN session ON session.id_sess=contenir.id_sess '.
    'WHERE id_exp=\''. $id .'\' AND nom_ress LIKE \'Salle%\'');
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

function get_ressources_from_util($id){
    $bdd = get_bdd();
    $query = "SELECT DISTINCT r.*
            FROM ressource AS r
            INNER JOIN contenir AS c ON c.id_ress = r.id_ress
            INNER JOIN session AS s ON s.id_sess = c.id_sess
            INNER JOIN experimentation AS e ON e.id_exp = s.id_exp
            INNER JOIN travailler AS t ON t.id_exp = e.id_exp
            WHERE t.id_util = :id_util";

    $stmt = $bdd->prepare($query);
    $stmt->execute([
        ':id_util' => ($id ?? 0)
    ]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return count($result);
}

/* #Utilisateur */
function get_participants_from_util($id){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM utilisateur as u '.
    'JOIN travailler AS t on t.id_util=u.id_util JOIN experimentation as e ON e.id_exp=t.id_exp JOIN projet as p ON p.id_proj=e.id_proj JOIN referencer as r ON r.id_proj=p.id_proj '.
    'WHERE r.id_util = '.$id .' AND u.type_util=\'externe\'');
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}
function get_participants_from_exp($id): array
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
        ':id_exp' => $id
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_EC_from_proj($id){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM utilisateur as u JOIN referencer as r on r.id_util=u.id_util WHERE r.id_proj=\''.$id.'\'');
    $result = $query->fetch(PDO::FETCH_ASSOC);

    return $result;
}

/* #Document */
function get_documents_from_exp($id): array{
    $bdd = get_bdd();
    $sql = "SELECT * FROM document WHERE document.id_exp = :id_exp";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([ ':id_exp' => $id ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function get_documents_from_proj($id): array{
    $bdd = get_bdd();
    $sql = "SELECT * FROM document JOIN experimentation as e on e.id_exp=document.id_exp WHERE e.id_proj = :id_proj";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([ ':id_proj' => $id ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* #Incident */
function get_incidents_from_exp($id): array{
    $bdd = get_bdd();
    $sql = "SELECT * FROM incident AS i INNER JOIN session AS s ON s.id_sess = i.id_sess WHERE s.id_exp = :id_exp";

    $stmt = $bdd->prepare($sql);
    $stmt->execute([ ':id_exp' => $id ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*-----------------------------------------
            Insert
-----------------------------------------*/
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

function insert_incident()
{
    // 1. Récupération et nettoyage des données du POST
    $titreIncid = trim((string) ($post['titre_incid'] ?? ''));
    $descIncid  = trim((string) ($post['desc_incid'] ?? ''));
    $idSess     = ($post['id_sess'] ?? 0);

    // 2. Vérification des champs obligatoires (id_sess est NOT NULL et FK)
    if ($titreIncid === '' || $idSess <= 0) {
        return false;
    }

    // 3. Requête préparée d'insertion (id_incid est géré par AUTO_INCREMENT)
    $bdd = get_bdd();
    $sql = "INSERT INTO incident (titre_incid, desc_incid, id_sess)
            VALUES (:titre_incid, :desc_incid, :id_sess)";

    $stmt = $bdd->prepare($sql);

    $stmt->execute([
        ':titre_incid' => $titreIncid,
        ':desc_incid'  => $descIncid,
        ':id_sess'     => $idSess
    ]);
}
?>