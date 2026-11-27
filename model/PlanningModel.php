<?php

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
        $cat  = detect_resource_category($desc);

        $resources[] = [
            'id_ress'            => (int) $row['id_ress'],
            'nom_ress'           => (string) ($row['nom_ress'] ?? ''),
            'desc_ress'          => $desc,
            'categorie_detectee' => $cat['label'],
            'categorie_classe'   => $cat['class'],
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
 * Génère la liste des semaines disponibles pour le sélecteur de planning.
 */
function get_planning_weeks(): array
{
    return [
        ['code' => 'S40', 'label' => '28 sept. 26', 'start_date' => '2026-09-28'],
        ['code' => 'S41', 'label' => '05 oct. 26',  'start_date' => '2026-10-05'],
        ['code' => 'S42', 'label' => '12 oct. 26',  'start_date' => '2026-10-12'],
        ['code' => 'S43', 'label' => '19 oct. 26',  'start_date' => '2026-10-19'],
        ['code' => 'S44', 'label' => '26 oct. 26',  'start_date' => '2026-10-26'],
        ['code' => 'S45', 'label' => '02 nov. 26',  'start_date' => '2026-11-02'],
        ['code' => 'S46', 'label' => '09 nov. 26',  'start_date' => '2026-11-09'],
        ['code' => 'S47', 'label' => '16 nov. 26',  'start_date' => '2026-11-16'],
        ['code' => 'S48', 'label' => '23 nov. 26',  'start_date' => '2026-11-23'],
        ['code' => 'S49', 'label' => '30 nov. 26',  'start_date' => '2026-11-30'],
    ];
}

/**
 * Calcule les 5 jours ouvrés (Lun-Ven) à partir de la date de début de semaine.
 */
function get_week_days(string $startDate): array
{
    setlocale(LC_TIME, 'fr_FR.UTF-8', 'fr_FR', 'French');
    $labels = ['LUNDI', 'MARDI', 'MERCREDI', 'JEUDI', 'VENDREDI'];
    $months = [
        1 => 'Janv.', 2 => 'Févr.', 3 => 'Mars', 4 => 'Avr.',
        5 => 'Mai',   6 => 'Juin',  7 => 'Juil.', 8 => 'Août',
        9 => 'Sept.', 10 => 'Oct.', 11 => 'Nov.', 12 => 'Déc.'
    ];

    $days = [];
    $base = new DateTimeImmutable($startDate);
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
 * Récupère les créneaux/réservations d'une ressource pour une semaine donnée.
 * Adapte les noms de tables/colonnes si ton schéma de session/réservation diffère.
 */
function get_resource_bookings_for_week(int $ressourceId, string $weekStartDate): array
{
    if ($ressourceId <= 0) {
        return [];
    }

    $bdd = get_bdd();
    $startDt = new DateTimeImmutable($weekStartDate . ' 00:00:00');
    $endDt   = $startDt->modify('+5 days');

    $tones = ['blue', 'orange', 'green', 'purple'];
    $bookings = [];

    try {
        // Requête préparée sur SESSION / PROJET / UTILISATEUR (à ajuster selon les colonnes exactes de ta BDD)
        $sql = 'SELECT s.id_sess, s.date_debut, s.date_fin, p.nom_proj, u.nom_util, u.prenom_util
                FROM SESSION s
                LEFT JOIN PROJET p ON s.id_proj = p.id_proj
                LEFT JOIN UTILISATEUR u ON p.id_resp = u.id_util
                WHERE s.id_ress = :ress_id
                  AND s.date_debut >= :start_dt
                  AND s.date_debut < :end_dt
                ORDER BY s.date_debut ASC';

        $stmt = $bdd->prepare($sql);
        $stmt->execute([
            ':ress_id'  => $ressourceId,
            ':start_dt' => $startDt->format('Y-m-d H:i:s'),
            ':end_dt'   => $endDt->format('Y-m-d H:i:s'),
        ]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $idx => $row) {
            $debut = new DateTimeImmutable((string) $row['date_debut']);
            $fin   = !empty($row['date_fin'])
                ? new DateTimeImmutable((string) $row['date_fin'])
                : $debut->modify('+1 hour');

            $dayIndex = (int) $debut->diff($startDt)->days;
            if ($dayIndex < 0 || $dayIndex > 4) {
                continue;
            }

            // Grille horaire démarrant à 08:00 (60px par heure)
            $startHourFloat = ((int) $debut->format('H')) + (((int) $debut->format('i')) / 60.0);
            $endHourFloat   = ((int) $fin->format('H')) + (((int) $fin->format('i')) / 60.0);

            $topPx    = max(0.0, ($startHourFloat - 8.0) * 60.0);
            $heightPx = max(28.0, ($endHourFloat - $startHourFloat) * 60.0);

            $person = trim(((string) ($row['prenom_util'] ?? '')) . ' ' . ((string) ($row['nom_util'] ?? '')));
            if ($person === '') {
                $person = 'Responsable';
            }

            $bookings[] = [
                'day_index'  => $dayIndex,
                'top_px'     => $topPx,
                'height_px'  => $heightPx,
                'title'      => (string) ($row['nom_proj'] ?: 'Créneau planifié'),
                'person'     => $person,
                'time_label' => $debut->format('H:i') . ' - ' . $fin->format('H:i'),
                'tone'       => $tones[$idx % count($tones)],
            ];
        }
    } catch (PDOException $e) {
        // Si la table SESSION n'est pas encore créée ou a un autre nom, on renvoie un tableau vide proprement
        return [];
    }

    return $bookings;
}

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
    $weeks       = get_planning_weeks();
    $activeWeek  = isset($get['week']) ? max(0, min(count($weeks) - 1, (int) $get['week'])) : 1;
    $days        = get_week_days($weeks[$activeWeek]['start_date']);

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

            $bookings = get_resource_bookings_for_week(
                $selectedRessourceId,
                $weeks[$activeWeek]['start_date']
            );
        } else {
            $selectedRessourceId = 0;
        }
    } catch (PDOException $e) {
        $dbError = 'Erreur lors du chargement des ressources ou du planning.';
    }

    return compact(
        'userNom',
        'userPrenom',
        'userRole',
        'initials',
        'searchQuery',
        'weeks',
        'activeWeek',
        'days',
        'filteredResources',
        'selectedRessourceId',
        'selectedRessource',
        'bookings',
        'dbError'
    );
}