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