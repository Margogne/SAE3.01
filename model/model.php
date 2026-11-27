<?php

function get_bdd()
{
    $serveur = "localhost";
    $base = "lab_project_grp04";
    $user = "root";
    $password = "";

    $dsn = "mysql:host=$serveur;dbname=$base;charset=utf8mb4";
    return new PDO($dsn, $user, $password);
}

function get_part_name($id)
{
    $bdd = get_bdd();
    $query = $bdd->query('SELECT nom_util FROM utilisateur WHERE id = ' . $id);
    return $query->fetch(PDO::FETCH_ASSOC);
}

function check_login($nom, $prenom)
{
    $bdd = get_bdd();
    $query = $bdd->prepare('SELECT * FROM utilisateur WHERE nom_util = ? AND prenom_util = ?');
    $query->execute([$nom, $prenom]);

    return $query->fetch(PDO::FETCH_ASSOC);
}

function get_all_projects()
{
    $bdd = get_bdd();
    $projets = $bdd->query('SELECT * FROM PROJET')->fetchAll(PDO::FETCH_ASSOC);

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
            'image' => '../assets/img/FondProjet.png',
            'domain' => 'Général',
            'mine' => false
        ];
    }

    return $projectsData;
}

function Get_Exp_WithSess()
{
    $bdd = get_bdd();
    $sql = 'SELECT DISTINCT e.* FROM EXPERIMENTATION e 
            JOIN SESSION s ON e.id_exp = s.id_exp 
            WHERE s.statut_sess = "En cours"';

    $experimentations = $bdd->query($sql)->fetchAll(PDO::FETCH_ASSOC);

    $ExperimentationsData = [];
    foreach ($experimentations as $e) {
        $ExperimentationsData[] = [
            'id' => $e['id_exp'],
            'category' => 'Recherche',
            'title' => $e['titre_exp'],
            'description' => $e['desc_exp'],
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

    $sqlChercheurs = 'SELECT u.nom_util, u.prenom_util 
                      FROM UTILISATEUR u
                      JOIN TRAVAILLER t ON u.id_util = t.id_util
                      WHERE t.id_exp = ?
                      ORDER BY t.num_ordre ASC';
    $qChercheurs = $bdd->prepare($sqlChercheurs);
    $qChercheurs->execute([$id_exp]);
    $exp['chercheurs'] = $qChercheurs->fetchAll(PDO::FETCH_ASSOC);

    $sqlRessources = 'SELECT DISTINCT r.nom_ress 
                      FROM RESSOURCE r
                      JOIN CONTENIR c ON r.id_ress = c.id_ress
                      JOIN SESSION s ON c.id_sess = s.id_sess
                      WHERE s.id_exp = ?';
    $qRessources = $bdd->prepare($sqlRessources);
    $qRessources->execute([$id_exp]);
    $exp['ressources'] = $qRessources->fetchAll(PDO::FETCH_COLUMN);

    $sqlSessions = 'SELECT * FROM SESSION WHERE id_exp = ? AND statut_sess = "En cours"';
    $qSessions = $bdd->prepare($sqlSessions);
    $qSessions->execute([$id_exp]);
    $exp['sessions'] = $qSessions->fetchAll(PDO::FETCH_ASSOC);

    return $exp;
}

function inscrire_utilisateur_session(int $idSess, int $idUtil): bool
{
    $bdd = get_bdd();
    $sql = 'INSERT IGNORE INTO PARTICIPER (id_sess, id_util) VALUES (:id_sess, :id_util)';
    $stmt = $bdd->prepare($sql);

    return $stmt->execute([
        ':id_sess' => $idSess,
        ':id_util' => $idUtil,
    ]);
}

function filter_experimentations(array $items, string $search): array
{
    if ($search === '') {
        return $items;
    }

    $needle = mb_strtolower($search, 'UTF-8');

    return array_filter($items, function ($item) use ($needle) {
        $haystack = mb_strtolower(
            ($item['title'] ?? '') . ' ' . ($item['category'] ?? '') . ' ' . ($item['description'] ?? ''),
            'UTF-8'
        );
        return mb_strpos($haystack, $needle) !== false;
    });
}

function init_part_acc_page(string $rawSearch, array $session): ?array
{
    if (!isset($session['user_id'])) {
        return null;
    }

    $search = trim($rawSearch);
    $projects = filter_experimentations(Get_Exp_WithSess(), $search);

    $userNom = $session['user_nom'] ?? 'Utilisateur';
    $userPrenom = $session['user_prenom'] ?? '';
    $userRole = $session['user_role'] ?? 'Participant';

    $initials = strtoupper(mb_substr($userPrenom, 0, 1) . mb_substr($userNom, 0, 1)) ?: 'U';

    return compact('search', 'projects', 'userNom', 'userPrenom', 'userRole', 'initials');
}

function get_participation($id_sess, $id_util)
{
    $bdd = get_bdd();

    $sql = 'SELECT * FROM PARTICIPER WHERE id_sess = ? AND id_util = ?';

    $query = $bdd->prepare($sql);
    $query->execute([$id_sess, $id_util]);
    $participation = $query->fetch(PDO::FETCH_ASSOC);

    return $participation ?: null;
}

function compute_initials(string $prenom, string $nom): string
{
    $p = trim($prenom);
    $n = trim($nom);

    $first = function_exists('mb_substr') ? mb_substr($p, 0, 1, 'UTF-8') : substr($p, 0, 1);
    $last = function_exists('mb_substr') ? mb_substr($n, 0, 1, 'UTF-8') : substr($n, 0, 1);

    $initials = strtoupper($first . $last);

    return $initials !== '' ? $initials : 'U';
}

function process_part_profil(array $post, array &$session): array
{
    $formData = [
        'age' => isset($post['age']) && $post['age'] !== '' ? (int) $post['age'] : '',
        'gender' => trim((string) ($post['gender'] ?? '')),
        'details' => trim((string) ($post['details'] ?? '')),
    ];

    $nom = trim((string) ($post['lastName'] ?? ''));
    $prenom = trim((string) ($post['firstName'] ?? ''));
    $age = (int) ($post['age'] ?? 0);
    $genre = $formData['gender'];

    if ($nom === '' || $prenom === '' || $age < 1 || $age > 120 || $genre === '') {
        return [
            'submitted' => false,
            'errorMessage' => 'Veuillez renseigner tous les champs obligatoires correctement.',
            'formData' => $formData,
        ];
    }

    $session['user_nom'] = $nom;
    $session['user_prenom'] = $prenom;

    return [
        'submitted' => true,
        'errorMessage' => '',
        'formData' => $formData,
    ];
}

/* ====================================
    préparation du profil utilisateur
   ==================================== */

function prepare_part_profil_page_data(array $session, string $method, array $post): ?array
{
    if (!isset($session['user_id'])) {
        return null;
    }

    $userId = (int) $session['user_id'];
    $dbUser = get_user_profile($userId) ?? [];

    $formData = [
        'lastName' => (string) ($dbUser['nom_util'] ?? $session['user_nom'] ?? ''),
        'firstName' => (string) ($dbUser['prenom_util'] ?? $session['user_prenom'] ?? ''),
        'gender' => (string) ($dbUser['genre_util'] ?? ''),
        'birthDate' => (string) ($dbUser['date_util'] ?? ''),
        'details' => (string) ($dbUser['spec_util'] ?? ''),
    ];

    $submitted = false;
    $errorMessage = '';

    if ($method === 'POST') {
        $formData['lastName'] = trim((string) ($post['lastName'] ?? ''));
        $formData['firstName'] = trim((string) ($post['firstName'] ?? ''));
        $formData['gender'] = trim((string) ($post['gender'] ?? ''));
        $formData['birthDate'] = trim((string) ($post['birthDate'] ?? ''));
        $formData['details'] = trim((string) ($post['details'] ?? ''));

        $dateValide = $formData['birthDate'] === '' || preg_match('/^\d{4}-\d{2}-\d{2}$/', $formData['birthDate']) === 1;

        if ($formData['lastName'] === '' || $formData['firstName'] === '' || $formData['gender'] === '' || !$dateValide) {
            $errorMessage = 'Veuillez renseigner tous les champs obligatoires correctement.';
        } else {
            $dateParam = $formData['birthDate'] !== '' ? $formData['birthDate'] : null;

            try {
                $ok = update_user_profile(
                    $userId,
                    $formData['lastName'],
                    $formData['firstName'],
                    $formData['gender'],
                    $dateParam,
                    $formData['details']
                );

                if ($ok) {
                    $_SESSION['user_nom'] = $formData['lastName'];
                    $_SESSION['user_prenom'] = $formData['firstName'];
                    $submitted = true;
                } else {
                    $errorMessage = "La mise à jour du profil n'a pas pu aboutir.";
                }
            } catch (PDOException $e) {
                $errorMessage = 'Erreur base de données lors de la mise à jour du profil.';
            }
        }
    }

    $userNom = (string) ($session['user_nom'] ?? 'Utilisateur');
    $userPrenom = (string) ($session['user_prenom'] ?? '');
    $userRole = (string) ($session['user_role'] ?? $dbUser['type_util'] ?? 'Participant');

    $prenomChar = function_exists('mb_substr') ? mb_substr($userPrenom, 0, 1, 'UTF-8') : substr($userPrenom, 0, 1);
    $nomChar = function_exists('mb_substr') ? mb_substr($userNom, 0, 1, 'UTF-8') : substr($userNom, 0, 1);
    $initiales = strtoupper($prenomChar . $nomChar) ?: 'U';

    return compact(
        'userNom',
        'userPrenom',
        'userRole',
        'initiales',
        'submitted',
        'errorMessage',
        'formData'
    );
}

function prepare_part_exp_sess_data(array $exp, array $get, array $session): array
{
    $userNom = $session['user_nom'] ?? 'Utilisateur';
    $userPrenom = $session['user_prenom'] ?? '';
    $userRole = $session['user_role'] ?? 'Participant';
    $initials = strtoupper(mb_substr($userPrenom, 0, 1) . mb_substr($userNom, 0, 1)) ?: 'U';

    $step = isset($get['step']) ? (int) $get['step'] : 0;
    $selectedSlot = isset($get['slot']) ? (int) $get['slot'] : null;

    $responsable = !empty($exp['chercheurs'])
        ? $exp['chercheurs'][0]['prenom_util'] . ' ' . $exp['chercheurs'][0]['nom_util']
        : 'Non renseigné';

    $site = !empty($exp['nom_lab'])
        ? $exp['nom_lab'] . (!empty($exp['ville_lab']) ? ' (' . $exp['ville_lab'] . ')' : '')
        : 'Non renseigné';

    $dateDebut = !empty($exp['datedeb_exp']) ? date('d/m/Y', strtotime($exp['datedeb_exp'])) : 'Non définie';
    $dateFin = !empty($exp['datefin_exp']) ? date('d/m/Y', strtotime($exp['datefin_exp'])) : 'Non définie';
    $equipements = !empty($exp['ressources']) ? implode(', ', $exp['ressources']) : 'Aucun équipement spécifique';

    $activeSessions = array_filter($exp['sessions'] ?? [], function ($s) {
        $statut = trim($s['statut_sess'] ?? '');
        return $statut !== '' && mb_strtolower($statut, 'UTF-8') !== 'terminé';
    });

    $registeredSessionId = isset($get['registered_session']) ? (int) $get['registered_session'] : null;
    $sessionId = isset($get['session_id']) ? (int) $get['session_id'] : 0;
    $userId = (int) ($session['user_id'] ?? 0);

    $inscriptionOk = false;
    $inscriptionErreur = '';

    if ($step === 3 && $sessionId > 0 && $userId > 0) {
        try {
            $inscriptionOk = inscrire_utilisateur_session($sessionId, $userId);
            if (!$inscriptionOk) {
                $inscriptionErreur = "L'enregistrement n'a pas pu aboutir.";
            }
        } catch (PDOException $e) {
            $inscriptionErreur = "Erreur base de données lors de l'inscription.";
        }
    }

    return compact(
        'userNom',
        'userPrenom',
        'userRole',
        'initials',
        'step',
        'selectedSlot',
        'responsable',
        'site',
        'dateDebut',
        'dateFin',
        'equipements',
        'activeSessions',
        'registeredSessionId',
        'sessionId',
        'inscriptionOk',
        'inscriptionErreur'
    );
}

/* =================================================================
   Gestion du profil utilisateur
   ========================================================== */

function get_user_profile(int $idUtil): ?array
{
    $bdd = get_bdd();
    $sql = 'SELECT id_util, nom_util, prenom_util, type_util, date_util, genre_util, langues_util, contr_util, spec_util
            FROM UTILISATEUR
            WHERE id_util = :id_util';
    $stmt = $bdd->prepare($sql);
    $stmt->execute([':id_util' => $idUtil]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user ?: null;
}

function update_user_profile(int $idUtil, string $nom, string $prenom, string $genre, ?string $dateUtil, string $spec): bool
{
    $bdd = get_bdd();
    $sql = 'UPDATE UTILISATEUR
            SET nom_util = :nom,
                prenom_util = :prenom,
                genre_util = :genre,
                date_util = :date_util,
                spec_util = :spec
            WHERE id_util = :id_util';

    $stmt = $bdd->prepare($sql);

    return $stmt->execute([
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':genre' => $genre,
        ':date_util' => $dateUtil,
        ':spec' => $spec,
        ':id_util' => $idUtil,
    ]);
}

/* =============================================================
   Gestion des messages
   ========================================================== */

function get_messages_for_user(int $userId, string &$dbError = ''): array
{
    $messages = [];
    $dbError = '';

    try {
        $bdd = get_bdd();

        $sql = 'SELECT e.id_mess,
                       e.id_util_receveur,
                       e.`id_util_expéditeur` AS id_util_expediteur,
                       e.date_envoi,
                       e.contenu_mess,
                       e.status_mess,
                       u.nom_util,
                       u.prenom_util
                FROM ECHANGER e
                LEFT JOIN UTILISATEUR u ON u.id_util = e.`id_util_expéditeur`
                WHERE e.id_util_receveur = :user_id
                ORDER BY e.date_envoi DESC, e.id_mess DESC';

        $stmt = $bdd->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $todayStr = date('Y-m-d');
        $yesterdayStr = date('Y-m-d', strtotime('-1 day'));

        foreach ($rows as $row) {
            $timestamp = !empty($row['date_envoi']) ? strtotime((string) $row['date_envoi']) : false;
            $dayStr = $timestamp ? date('Y-m-d', $timestamp) : '';

            if ($dayStr === $todayStr) {
                $period = 'Aujourd’hui';
            } elseif ($dayStr === $yesterdayStr) {
                $period = 'Hier';
            } else {
                $period = 'Plus tôt';
            }

            $senderName = trim(($row['prenom_util'] ?? '') . ' ' . ($row['nom_util'] ?? ''));
            if ($senderName === '') {
                $senderName = 'Expéditeur #' . (int) ($row['id_util_expediteur'] ?? 0);
            }

            $messages[] = [
                'id_mess' => (int) $row['id_mess'],
                'id_util_receveur' => (int) $row['id_util_receveur'],
                'id_util_expediteur' => (int) ($row['id_util_expediteur'] ?? 0),
                'period' => $period,
                'sender' => $senderName,
                'contenu_mess' => (string) ($row['contenu_mess'] ?? ''),
                'date_envoi' => $timestamp ? date('d/m/Y H:i', $timestamp) : (string) ($row['date_envoi'] ?? ''),
                'status_mess' => $row['status_mess'] ?? 0,
            ];
        }
    } catch (PDOException $e) {
        $dbError = 'Erreur SQL : ' . $e->getMessage();
    }

    return $messages;
}

function is_message_unread(array $message): bool
{
    return empty($message['status_mess']);
}

function filter_messages(array $messages, string $status, string $query): array
{
    if (!in_array($status, ['all', 'unread', 'read'], true)) {
        $status = 'all';
    }

    $needle = trim($query);

    return array_values(array_filter($messages, function ($item) use ($status, $needle) {
        $unread = is_message_unread($item);
        $matchesStatus = $status === 'all'
            || ($status === 'unread' && $unread)
            || ($status === 'read' && !$unread);

        $haystack = ($item['sender'] ?? '') . ' ' . ($item['contenu_mess'] ?? '') . ' ' . ($item['date_envoi'] ?? '');
        $matchesQuery = ($needle === '')
            || (stripos($haystack, $needle) !== false);

        return $matchesStatus && $matchesQuery;
    }));
}

function prepare_part_mess_page_data(array $session, array $get): ?array
{
    if (!isset($session['user_id'])) {
        return null;
    }

    $userId = (int) $session['user_id'];
    $userNom = (string) ($session['user_nom'] ?? 'Utilisateur');
    $userPrenom = (string) ($session['user_prenom'] ?? '');
    $userRole = (string) ($session['user_role'] ?? 'Participant');

    $initials = compute_initials($userPrenom, $userNom);

    $dbError = '';
    $messages = get_messages_for_user($userId, $dbError);

    $status = $get['status'] ?? 'all';
    if (!in_array($status, ['all', 'unread', 'read'], true)) {
        $status = 'all';
    }

    $query = trim((string) ($get['q'] ?? ''));

    $unreadCount = count(array_filter($messages, 'is_message_unread'));
    $readCount = count($messages) - $unreadCount;
    $filtered = filter_messages($messages, $status, $query);
    $periods = ['Aujourd’hui', 'Hier', 'Plus tôt'];

    return compact(
        'userNom',
        'userPrenom',
        'userRole',
        'initials',
        'dbError',
        'messages',
        'status',
        'query',
        'unreadCount',
        'readCount',
        'filtered',
        'periods'
    );
}

function delete_message(int $messageId, int $userId): bool
{
    $bdd = get_bdd();
    $sql = 'DELETE FROM ECHANGER WHERE id_mess = :id_mess AND id_util_receveur = :user_id';
    $stmt = $bdd->prepare($sql);

    return $stmt->execute([
        ':id_mess' => $messageId,
        ':user_id' => $userId,
    ]);
}

function gestion_message_delete(array $session, array $get, string $redirectPage = 'PartMess.php'): ?string
{
    $status = $get['status'] ?? 'all';
    if (!in_array($status, ['all', 'unread', 'read'], true)) {
        $status = 'all';
    }

    $query = trim((string) ($get['q'] ?? ''));

    // 1. Si l'utilisateur a cliqué sur la croix de suppression
    if (isset($get['delete_mess'])) {
        $messageId = (int) $get['delete_mess'];
        $userId = (int) ($session['user_id'] ?? 0);

        if ($messageId > 0 && $userId > 0) {
            delete_message($messageId, $userId);
        }

        $url = $redirectPage
            . '?status=' . urlencode($status)
            . '&q=' . urlencode($query)
            . '&flash=deleted';

        header('Location: ' . $url);
        exit();
    }

    // 2. Après redirection, on renvoie le message flash si demandé
    if (($get['flash'] ?? '') === 'deleted') {
        return 'Le message a bien été supprimé.';
    }

    return null;
}

function mark_message_as_read(int $messageId, int $userId): bool
{
    $bdd = get_bdd();
    $sql = 'UPDATE ECHANGER
            SET status_mess = 1
            WHERE id_mess = :id_mess AND id_util_receveur = :user_id';
    $stmt = $bdd->prepare($sql);

    return $stmt->execute([
        ':id_mess' => $messageId,
        ':user_id' => $userId,
    ]);
}

function gestion_message_mark_read(array $session, array $get, string $redirectPage = 'PartMess.php'): ?string
{
    $status = $get['status'] ?? 'all';
    if (!in_array($status, ['all', 'unread', 'read'], true)) {
        $status = 'all';
    }

    $query = trim((string) ($get['q'] ?? ''));

    if (isset($get['mark_read'])) {
        $messageId = (int) $get['mark_read'];
        $userId = (int) ($session['user_id'] ?? 0);

        if ($messageId > 0 && $userId > 0) {
            mark_message_as_read($messageId, $userId);
        }

        $url = $redirectPage
            . '?status=' . urlencode($status)
            . '&q=' . urlencode($query)
            . '&flash=read';

        header('Location: ' . $url);
        exit();
    }

    if (($get['flash'] ?? '') === 'read') {
        return 'Le message a bien été marqué comme lu.';
    }

    return null;
}

/*  ===========================================
    GESTIONNAIRE - Gestion des projet
    =========================================== */

function get_projects_for_listing(int $userId): array
{
    $bdd = get_bdd();

    $sql = 'SELECT p.id_proj,
                    p.nom_proj,
                    p.desc_proj,
                    p.datedeb_proj,
                    p.datefin_proj,
                    p.id_lab,
                    l.nom_lab,
                    l.ville_lab,
                    EXISTS (
                        SELECT 1
                        FROM REFERENCER r_mine
                        WHERE r_mine.id_proj = p.id_proj
                            AND r_mine.id_util = :user_id
                    ) AS is_mine
            FROM PROJET p
            LEFT JOIN LABORATOIRE l ON l.id_lab = p.id_lab
            ORDER BY p.id_proj ASC';

    $stmt = $bdd->prepare($sql);
    $stmt->execute([':user_id' => $userId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sqlRef = 'SELECT r.id_proj, r.nom_role, u.prenom_util, u.nom_util
                FROM REFERENCER r
                JOIN UTILISATEUR u ON u.id_util = r.id_util
                WHERE r.nom_role IN (\'responsable\', \'gestionnaire\')
                ORDER BY r.id_role ASC';
    $refRows = $bdd->query($sqlRef)->fetchAll(PDO::FETCH_ASSOC);

    $ownersByProject = [];
    foreach ($refRows as $ref) {
        $pid = (int) $ref['id_proj'];
        if (!isset($ownersByProject[$pid])) {
            $fullName = trim(($ref['prenom_util'] ?? '') . ' ' . ($ref['nom_util'] ?? ''));
            $ownersByProject[$pid] = $fullName;
        }
    }

    $projects = [];
    foreach ($rows as $row) {
        $pid = (int) $row['id_proj'];
        $projects[] = [
            'id' => $pid,
            'title' => (string) ($row['nom_proj'] ?? ''),
            'description' => (string) ($row['desc_proj'] ?? ''),
            'datedeb' => (string) ($row['datedeb_proj'] ?? ''),
            'datefin' => (string) ($row['datefin_proj'] ?? ''),
            'lab' => (string) ($row['nom_lab'] ?? ''),
            'city' => (string) ($row['ville_lab'] ?? ''),
            'owner' => $ownersByProject[$pid] ?? '',
            'mine' => !empty($row['is_mine']),
        ];
    }

    return $projects;
}

function filter_projects_listing(array $projects, string $query, bool $mineOnly): array
{
    $needle = mb_strtolower(trim($query), 'UTF-8');

    return array_values(array_filter($projects, function ($project) use ($needle, $mineOnly) {
        $haystack = mb_strtolower(
            ($project['title'] ?? '') . ' ' .
            ($project['description'] ?? '') . ' ' .
            ($project['lab'] ?? '') . ' ' .
            ($project['owner'] ?? ''),
            'UTF-8'
        );

        $matchesQuery = ($needle === '') || (mb_strpos($haystack, $needle) !== false);
        $matchesMine = (!$mineOnly) || !empty($project['mine']);

        return $matchesQuery && $matchesMine;
    }));
}

function init_gest_list_project_page(array $get, array $session): ?array
{
    if (!isset($session['user_id'])) {
        return null;
    }

    $userId = (int) $session['user_id'];
    $userNom = (string) ($session['user_nom'] ?? '');
    $userPrenom = (string) ($session['user_prenom'] ?? '');
    $userRole = (string) ($session['user_role'] ?? '');
    $initials = compute_initials($userPrenom, $userNom);

    $query = trim((string) ($get['search'] ?? ''));
    $mineOnly = isset($get['mine']) && (string) $get['mine'] === '1';

    $dbError = '';
    $projects = [];

    try {
        $allProjects = get_projects_for_listing($userId);
        $projects = filter_projects_listing($allProjects, $query, $mineOnly);
    } catch (PDOException $e) {
        $dbError = $e->getMessage();
    }

    return compact(
        'userNom',
        'userPrenom',
        'userRole',
        'initials',
        'query',
        'mineOnly',
        'projects',
        'dbError'
    );
}