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
    $query = $bdd->query('SELECT * FROM experimentation JOIN travailler on experimentation.id_exp=travailler.id_exp 
    WHERE id_util = '.$_SESSION['user_id'].' ORDER BY datefin_exp DESC LIMIT 5') ;
    //$query = $bdd->query('SELECT * FROM ressource');
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function get_nb_experimentations(){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM experimentation JOIN travailler on experimentation.id_exp=travailler.id_exp 
    WHERE id_util = '.$_SESSION['user_id'].' ORDER BY datefin_exp DESC LIMIT 5') ;
    //$query = $bdd->query('SELECT * FROM ressource');
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return count($result);
}

function get_nb_proj(){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM projet JOIN referencer on referencer.id_proj=projet.id_proj 
    WHERE id_util = '.$_SESSION['user_id']) ;
    //$query = $bdd->query('SELECT * FROM ressource');
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return count($result);
}

function get_nb_participants(){
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM utilisateur as u '.
    'JOIN travailler AS t on t.id_util=u.id_util JOIN experimentation as e ON e.id_exp=t.id_exp JOIN projet as p ON p.id_proj=e.id_proj JOIN referencer as r ON r.id_proj=p.id_proj '.
    'WHERE r.id_util = '.$_SESSION['user_id'] .' AND u.type_util=\'externe\'');
    //$query = $bdd->query('SELECT * FROM ressource');
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return count($result);
}

function get_nb_ressources(){
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
        ':id_util' => ($_SESSION['user_id'] ?? 0)
    ]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return count($result);
}
function get_prochaine_echeance(): string
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
        ':id_util' => (int) ($_SESSION['user_id'] ?? 0)
    ]);

    $prochaine = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$prochaine || empty($prochaine['datefin_exp'])) {
        return 'Aucune échéance à venir';
    }

    // Formatage en JJ/MM/AAAA pour l'affichage
    $date = new DateTimeImmutable($prochaine['datefin_exp']);
    return $date->format('d/m/Y');
    // Ou avec le titre : return $date->format('d/m/Y') . ' (' . htmlspecialchars($prochaine['titre_exp']) . ')';
}

?>