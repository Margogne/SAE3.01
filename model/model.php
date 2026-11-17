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

function get_part_name($id)
{
    $bdd = get_bdd();
    $query = $bdd->query('SELECT nom_util FROM utilisateur WHERE id = ' . $id);
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function check_login($nom, $prenom)
{
    $bdd = get_bdd();
    $query = $bdd->prepare('SELECT * FROM utilisateur WHERE nom_util = ? AND prenom_util = ?');
    $query->execute([$nom, $prenom]);
    
    // Retourne les informations de l'utilisateur si trouvé, ou false sinon
    return $query->fetch(PDO::FETCH_ASSOC);
}

function get_all_projects() {
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM PROJET');
    $projets = $query->fetchAll(PDO::FETCH_ASSOC);
    
    $projectsData = [];
    foreach ($projets as $p) {
        $projectsData[] = [
            'id' => $p['id_proj'],
            'category' => 'Recherche',
            'title' => $p['nom_proj'],
            'description' => $p['desc_proj'],
            'owner' => 'Responsable projet',
            'status' => 'En cours',
            'statusColor' => '#34d399',
            'image' => '../assets/img/FondProjet.png', // Image par défaut
            'domain' => 'Général',
            'mine' => false
        ];
    }
    return $projectsData;
}

function Get_Exp_WithSess() {
    $bdd = get_bdd();
    // On sélectionne les expérimentations qui possèdent au moins une session "En cours"
    $sql = 'SELECT DISTINCT e.* FROM EXPERIMENTATION e 
            JOIN SESSION s ON e.id_exp = s.id_exp 
            WHERE s.statut_sess = "En cours"';
            
    $query = $bdd->query($sql);
    $experimentations = $query->fetchAll(PDO::FETCH_ASSOC);
    
    $ExperimentationsData = [];
    foreach ($experimentations as $e) {
        $ExperimentationsData[] = [
            'id' => $e['id_exp'],
            'category' => 'Recherche',
            'title' => $e['titre_exp'],
            'description' => $e['desc_exp'],
            //'owner' => 'Responsable expérimentation',
            //'status' => 'En cours',
            'statusColor' => '#34d399',
            'image' => '../assets/img/ExperimentationStandard.png',
            'domain' => 'Général',
            'mine' => false
        ];
    }
    return $ExperimentationsData;
}

function buildFilterQuery(array $extra = []): string
{
  $params = [];
  if (!empty($_GET['search'])) {
    $params['search'] = $_GET['search'];
  }
  $merged = array_merge($params, $extra);
  return empty($merged) ? 'PartAcc.php' : 'PartAcc.php?' . http_build_query($merged);
}

function get_exp_details($id_exp)
{
    $bdd = get_bdd();

    // 1. Récupération des infos de l'expérimentation, du projet et du laboratoire associé
    $sql = 'SELECT e.*, p.nom_proj, l.nom_lab, l.ville_lab
            FROM EXPERIMENTATION e
            LEFT JOIN PROJET p ON e.id_proj = p.id_proj
            LEFT JOIN LABORATOIRE l ON p.id_lab = l.id_lab
            WHERE e.id_exp = ?';
    $query = $bdd->prepare($sql);
    $query->execute([$id_exp]);
    $exp = $query->fetch(PDO::FETCH_ASSOC);

    if (!$exp) {
        return null;
    }

    // 2. Récupération des chercheurs associés (table UTILISATEUR via TRAVAILLER)
    $sqlChercheurs = 'SELECT u.nom_util, u.prenom_util 
                      FROM UTILISATEUR u
                      JOIN TRAVAILLER t ON u.id_util = t.id_util
                      WHERE t.id_exp = ?
                      ORDER BY t.num_ordre ASC';
    $qChercheurs = $bdd->prepare($sqlChercheurs);
    $qChercheurs->execute([$id_exp]);
    $exp['chercheurs'] = $qChercheurs->fetchAll(PDO::FETCH_ASSOC);

    // 3. Récupération des ressources/équipements réservés pour les sessions de cette expérimentation
    $sqlRessources = 'SELECT DISTINCT r.nom_ress 
                      FROM RESSOURCE r
                      JOIN CONTENIR c ON r.id_ress = c.id_ress
                      JOIN SESSION s ON c.id_sess = s.id_sess
                      WHERE s.id_exp = ?';
    $qRessources = $bdd->prepare($sqlRessources);
    $qRessources->execute([$id_exp]);
    $exp['ressources'] = $qRessources->fetchAll(PDO::FETCH_COLUMN);

    // 4. Récupération des sessions "En cours" associées (utile pour la suite / les créneaux)
    $sqlSessions = 'SELECT * FROM SESSION WHERE id_exp = ? AND statut_sess = "En cours"';
    $qSessions = $bdd->prepare($sqlSessions);
    $qSessions->execute([$id_exp]);
    $exp['sessions'] = $qSessions->fetchAll(PDO::FETCH_ASSOC);

    return $exp;
}