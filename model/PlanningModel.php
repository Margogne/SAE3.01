<?php
/**
 * Initialise toutes les données nécessaires à la page planning.php.
 */

function init_planning_page(array $get, array $session): ?array
{
    if (!isset($session['user_id'])) {
        return null;
    }

    $userNom    = (string) ($session['user_nom'] ?? 'Utilisateur');
    $userPrenom = (string) ($session['user_prenom'] ?? '');
    $userRole   = (string) ($session['user_role'] ?? 'Gestionnaire');
    $initials   = compute_initials($userPrenom, $userNom);

    $searchQuery = trim((string) ($get['q'] ?? ''));

    $requestedDate  = (string) ($get['week_start'] ?? date('Y-m-d'));
    $currentMonday  = get_monday_of_date($requestedDate);
    $activeMonday   = $currentMonday->format('Y-m-d');
    $prevWeekStart  = $currentMonday->modify('-7 days')->format('Y-m-d');
    $nextWeekStart  = $currentMonday->modify('+7 days')->format('Y-m-d');
    $todayWeekStart = get_monday_of_date(date('Y-m-d'))->format('Y-m-d');

    $weeks = build_surrounding_weeks($currentMonday, 6, 8);
    $days  = get_week_days($activeMonday);

    $dbError = '';
    $allResources = [];
    $filteredResources = [];
    $selectedRessourceId = isset($get['ress_id']) ? (int) $get['ress_id'] : 0;
    $selectedRessource = null;
    $bookings = [];

    try {
        $allResources = get_all_resources();
        $filteredResources = filter_resources_by_query($allResources, $searchQuery);

        if (!empty($filteredResources)) {
            $matchIds = array_column($filteredResources, 'id_ress');
            if (!in_array($selectedRessourceId, $matchIds, true)) {
                $selectedRessourceId = (int) $filteredResources[0]['id_ress'];
            }

            foreach ($filteredResources as $res) {
                if ((int) $res['id_ress'] === $selectedRessourceId) {
                    $selectedRessource = $res;
                    break;
                }
            }

            $bookings = get_resource_reservations_for_week($selectedRessourceId, $activeMonday);
        } else {
            $selectedRessourceId = 0;
        }
    } catch (PDOException $e) {
        $dbError = 'Erreur lors du chargement des ressources ou des réservations.';
    }

    $resetUrl = build_planning_url([
        'week_start' => $activeMonday,
        'ress_id'    => $selectedRessourceId > 0 ? $selectedRessourceId : null,
    ]);

    $todayUrl = build_planning_url([
        'week_start' => $todayWeekStart,
        'ress_id'    => $selectedRessourceId > 0 ? $selectedRessourceId : null,
        'q'          => $searchQuery !== '' ? $searchQuery : null,
    ]);

    $prevWeekUrl = build_planning_url([
        'week_start' => $prevWeekStart,
        'ress_id'    => $selectedRessourceId > 0 ? $selectedRessourceId : null,
        'q'          => $searchQuery !== '' ? $searchQuery : null,
    ]);

    $nextWeekUrl = build_planning_url([
        'week_start' => $nextWeekStart,
        'ress_id'    => $selectedRessourceId > 0 ? $selectedRessourceId : null,
        'q'          => $searchQuery !== '' ? $searchQuery : null,
    ]);

    return compact(
        'userNom',
        'userPrenom',
        'userRole',
        'initials',
        'searchQuery',
        'activeMonday',
        'prevWeekUrl',
        'nextWeekUrl',
        'todayUrl',
        'resetUrl',
        'weeks',
        'days',
        'filteredResources',
        'selectedRessourceId',
        'selectedRessource',
        'bookings',
        'dbError'
    );
}

/**
 * Détecte la catégorie d'une ressource à partir de desc_ress ("Salle" ou "Matériel").
 */
function detect_resource_category(string $descRess): array
{
    $lower = mb_strtolower($descRess, 'UTF-8');

    if (mb_strpos($lower, 'salle') !== false) {
        return ['label' => 'Salle', 'class' => 'badge-salle'];
    }

    if (mb_strpos($lower, 'matériel') !== false || mb_strpos($lower, 'materiel') !== false) {
        return ['label' => 'Matériel', 'class' => 'badge-materiel'];
    }

    return ['label' => 'Ressource', 'class' => 'badge-autre'];
}

/**
 * Récupère toutes les ressources depuis la table RESSOURCE.
 */
function get_all_resources(): array
{
    $bdd = get_bdd();
    $sql = 'SELECT id_ress, nom_ress, desc_ress
            FROM RESSOURCE
            ORDER BY nom_ress ASC, id_ress ASC';
    $rows = $bdd->query($sql)->fetchAll(PDO::FETCH_ASSOC);

    $resources = [];
    foreach ($rows as $row) {
        $desc = (string) ($row['desc_ress'] ?? '');
        $cat = detect_resource_category($desc);

        $resources[] = [
            'id_ress' => (int) $row['id_ress'],
            'nom_ress' => (string) ($row['nom_ress'] ?? ''),
            'desc_ress' => $desc,
            'categorie_detectee' => $cat['label'],
            'categorie_classe' => $cat['class'],
        ];
    }

    return $resources;
}

/**
 * Filtre les ressources selon une requête texte (nom, description ou mot-clé Salle / Matériel).
 */
function filter_resources_by_query(array $resources, string $query): array
{
    $needle = mb_strtolower(trim($query), 'UTF-8');
    if ($needle === '') {
        return array_values($resources);
    }

    $terms = preg_split('/\s+/u', $needle) ?: [];

    return array_values(array_filter($resources, function ($res) use ($terms) {
        $haystack = mb_strtolower(
            ($res['nom_ress'] ?? '') . ' ' .
            ($res['desc_ress'] ?? '') . ' ' .
            ($res['categorie_detectee'] ?? ''),
            'UTF-8'
        );

        foreach ($terms as $term) {
            if ($term !== '' && mb_strpos($haystack, $term) === false) {
                return false;
            }
        }
        return true;
    }));
}

/**
 * Construit une URL de navigation vers planning.php en conservant les paramètres actifs.
 */
function build_planning_url(array $params = []): string
{
    $filtered = array_filter($params, function ($value) {
        return $value !== null && $value !== '';
    });

    if (empty($filtered)) {
        return 'planning.php';
    }

    return 'planning.php?' . http_build_query($filtered, '', '&', PHP_QUERY_RFC3986);
}

/**
 * Normalise une date (YYYY-MM-DD) vers le lundi de sa semaine.
 */
function get_monday_of_date(string $dateStr): DateTimeImmutable
{
    $dt = DateTimeImmutable::createFromFormat('Y-m-d', $dateStr);
    if (!$dt) {
        $dt = new DateTimeImmutable('today');
    }
    $dayOfWeek = (int) $dt->format('N'); // 1 (lundi) à 7 (dimanche)
    return $dt->modify('-' . ($dayOfWeek - 1) . ' days')->setTime(0, 0, 0);
}

/**
 * Génère une fenêtre glissante de semaines autour du lundi courant ($currentMonday),
 * permettant de naviguer indéfiniment vers le passé ou le futur.
 */
function build_surrounding_weeks(DateTimeImmutable $currentMonday, int $radiusBefore = 6, int $radiusAfter = 8): array
{
    $months = [
        1 => 'janv.', 2 => 'févr.', 3 => 'mars', 4 => 'avr.',
        5 => 'mai',   6 => 'juin',  7 => 'juil.', 8 => 'août',
        9 => 'sept.', 10 => 'oct.', 11 => 'nov.', 12 => 'déc.'
    ];

    $weeks = [];
    for ($offset = -$radiusBefore; $offset <= $radiusAfter; $offset++) {
        $monday = $currentMonday->modify(($offset >= 0 ? '+' : '') . ($offset * 7) . ' days');
        $m = (int) $monday->format('n');
        $weeks[] = [
            'start_date' => $monday->format('Y-m-d'),
            'code'       => 'S' . $monday->format('W'),
            'label'      => $monday->format('d') . ' ' . ($months[$m] ?? $monday->format('m')) . ' ' . $monday->format('y'),
        ];
    }

    return $weeks;
}

/**
 * Calcule les 5 jours ouvrés (Lun-Ven) à partir du lundi de la semaine.
 */
function get_week_days(string $startDate): array
{
    $labels = ['LUNDI', 'MARDI', 'MERCREDI', 'JEUDI', 'VENDREDI'];
    $months = [
        1 => 'Janv.', 2 => 'Févr.', 3 => 'Mars', 4 => 'Avr.',
        5 => 'Mai',   6 => 'Juin',  7 => 'Juil.', 8 => 'Août',
        9 => 'Sept.', 10 => 'Oct.', 11 => 'Nov.', 12 => 'Déc.'
    ];

    $days = [];
    $base = get_monday_of_date($startDate);
    for ($i = 0; $i < 5; $i++) {
        $d = $base->modify("+{$i} days");
        $m = (int) $d->format('n');
        $days[] = [
            'label' => $labels[$i],
            'date'  => $d->format('d') . ' ' . ($months[$m] ?? $d->format('m')),
        ];
    }

    return $days;
}

/**
 * Récupère les créneaux / horaires d'usage de la table RESERVATION pour une ressource et une semaine.
 */
function get_resource_reservations_for_week(int $ressourceId, string $weekStartDate): array
{
    if ($ressourceId <= 0) {
        return [];
    }

    $bdd = get_bdd();
    $startDt = get_monday_of_date($weekStartDate);
    $endDt   = $startDt->modify('+7 days');

    $tones = ['blue', 'orange', 'green', 'purple'];
    $bookings = [];

    $sql = 'SELECT r.id_res,
                   r.datedeb_res,
                   r.datefin_res,
                   r.id_sess,
                   r.id_ress,
                   s.statut_sess,
                   e.titre_exp
            FROM RESERVATION r
            INNER JOIN SESSION s ON r.id_sess = s.id_sess
            LEFT JOIN EXPERIMENTATION e ON s.id_exp = e.id_exp
            WHERE r.id_ress = :ress_id
              AND r.datedeb_res < :end_dt
              AND r.datefin_res > :start_dt
            ORDER BY r.datedeb_res ASC';

    $stmt = $bdd->prepare($sql);
    $stmt->execute([
        ':ress_id'  => $ressourceId,
        ':start_dt' => $startDt->format('Y-m-d H:i:s'),
        ':end_dt'   => $endDt->format('Y-m-d H:i:s'),
    ]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $idx => $row) {
        $debut = new DateTimeImmutable((string) $row['datedeb_res']);
        $fin   = new DateTimeImmutable((string) $row['datefin_res']);

        $dayDiff = (int) $startDt->diff($debut->setTime(0, 0, 0))->format('%r%a');
        if ($dayDiff < 0 || $dayDiff > 4) {
            continue;
        }

        // Grille de 07:00 à 20:00 (60px par heure)
        $gridStartHour  = 7.0;
        $startHourFloat = ((int) $debut->format('H')) + (((int) $debut->format('i')) / 60.0);
        $endHourFloat   = ((int) $fin->format('H')) + (((int) $fin->format('i')) / 60.0);

        $topPx    = max(0.0, ($startHourFloat - $gridStartHour) * 60.0);
        $heightPx = max(28.0, ($endHourFloat - $startHourFloat) * 60.0);

        $titre = !empty($row['titre_exp'])
            ? (string) $row['titre_exp']
            : ('Session #' . (int) $row['id_sess']);

        $statut = (string) ($row['statut_sess'] ?? '');

        $bookings[] = [
            'id_res'     => (int) $row['id_res'],
            'day_index'  => $dayDiff,
            'top_px'     => $topPx,
            'height_px'  => $heightPx,
            'title'      => $titre,
            'person'     => $statut !== '' ? 'Session #' . (int) $row['id_sess'] . ' (' . $statut . ')' : 'Session #' . (int) $row['id_sess'],
            'time_label' => $debut->format('H:i') . ' - ' . $fin->format('H:i'),
            'tone'       => $tones[$idx % count($tones)],
        ];
    }

    return $bookings;
}