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
    // On prépare la requête pour chercher l'utilisateur par son nom et son prénom
    // Remplace 'nom' et 'prenom' par les noms exacts de tes colonnes dans la table SQL si besoin
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
            'category' => 'Recherche', // Valeur par défaut ou liée au domaine
            'title' => $p['nom_proj'],
            'description' => $p['desc_proj'],
            'owner' => 'Responsable projet', // Peut être enrichi via la table FAIRE / UTILISATEUR si besoin
            'status' => 'En cours',
            'statusColor' => '#34d399',
            'image' => '../assets/img/FondProjet.png', // Image par défaut demandée
            'domain' => 'Général',
            'mine' => false // Logique à adapter si l'utilisateur participe au projet
        ];
    }
    return $projectsData;
}