<?php
session_start();
require_once '../model/model.php';
require_once '../model/PlanningModel.php';

$page = init_planning_page($_GET, $_SESSION);
if ($page === null) {
    header('Location: ID.php');
    exit();
}

extract($page);
$hasResource = $selectedRessourceId > 0;
$hasQuery = $searchQuery !== '';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Planning des Salles &amp; Équipements - Le Mans Université</title>
  <link rel="stylesheet" href="../style/PartGenStyle.css" />
  <link rel="stylesheet" href="../style/PlanningStyle.css" />
  <link rel="stylesheet" href="../style/ECheader.css" />
</head>

<body>
  <div class="app-shell">

    <!-- HEADER de EC -->
    <?php
      $_POST['trait'] = '5';
      include '../model/ECheaderModel.php';
    ?>

    <!--BANDEAU Titre -->
    <section class="title-strip">
      <div>
        <h1><span class="fa">&#xf073;</span> Planning des Salles &amp; Équipements</h1>
        <p>Consultez les horaires d'usage réservés et naviguez librement entre les semaines.</p>
      </div>
    </section>

    <!-- corp de page -->
    <main class="planning-main">

      <!-- Gestion des Erreur -->
      <?php if ($dbError !== ''): ?>
        <div class="alert-error" role="alert">
          <?= htmlspecialchars($dbError) ?>
        </div>
      <?php endif; ?>

      <!-- Section Filtres -->
      <section class="planning-filters" aria-label="Recherche de ressources">

        <!-- Zone de Recherche -->
        <form method="GET" action="Planning.php" class="resource-search-form" id="resourceSearchForm">
          <input type="hidden" name="week_start" value="<?= htmlspecialchars($activeMonday) ?>" />
          <?php if ($selectedRessourceId > 0): ?>
            <input type="hidden" name="ress_id" value="<?= (int) $selectedRessourceId ?>" />
          <?php endif; ?>

          <!-- Barre de recherche -->
          <label class="resource-search-label" for="resourceSearchInput">
            <span class="sr-only">Rechercher une ressource</span>
            <span class="fa search-icon">&#xf002;</span>
            <input
              type="search"
              id="resourceSearchInput"
              name="q"
              value="<?= htmlspecialchars($searchQuery) ?>"
              placeholder="Rechercher une ressource (nom, Salle, Matériel, bâtiment, équipement...)"
            />
          </label>

          <!-- Bouton de Recherche -->
          <button type="submit" class="btn-search-submit">Rechercher</button>

        </form>
        
        <!-- note bilan de presence des ressources -->
        <div class="resource-results-bar" id="resourceResultsInfo">
          <span><?= count($filteredResources) ?> ressource(s) trouvée(s)</span><!-- j'adore la fonction count() -->
        </div>

        <!-- Affichage des ressources -->
        <div class="resource-pills" id="resourceList" role="listbox" aria-label="Ressources correspondantes">
          <?php if (empty($filteredResources)) : ?>
            <p class="no-resource-match">Aucune ressource ne correspond à cette recherche.</p>
          <?php else : foreach ($filteredResources as $ress) :
            $isSelected = (int)$ress['id_ress'] === (int)$selectedRessourceId;
          ?>
            <a href="<?= htmlspecialchars(build_planning_url([
              'week_start' => $activeMonday,
              'ress_id' => (int)$ress['id_ress'],
              'q' => $searchQuery ?: null
            ])) ?>"
              class="resource-pill <?= $isSelected ? 'active' : '' ?>"
              aria-selected="<?= $isSelected ? 'true' : 'false' ?>">

              <span class="resource-badge <?= htmlspecialchars($ress['categorie_classe']) ?>">
                <?= htmlspecialchars($ress['categorie_detectee']) ?>
              </span>
              <strong class="resource-pill-title"><?= htmlspecialchars($ress['nom_ress']) ?></strong>
              <small class="resource-pill-desc"><?= htmlspecialchars($ress['desc_ress']) ?></small>
            </a>
          <?php endforeach; endif; ?>
        </div>

      </section>

      <!-- Bloc Calendrier -->
      <section class="calendar-card" aria-label="Planning hebdomadaire">
      
        <!-- bar de manip  -->
        <div class="calendar-toolbar">

          <!-- affichage pour ressource -->
          <div class="selected-resource-summary">
            <span>Ressource affichée :</span>
            <strong>
              <?= $selectedRessource ? htmlspecialchars($selectedRessource['nom_ress']) : 'Aucune ressource sélectionnée' ?>
            </strong>
            <?php if ($selectedRessource): ?>
              <small><?= htmlspecialchars($selectedRessource['desc_ress']) ?></small>
            <?php endif; ?>
          </div>
        
          <!-- Bar bouton calendrier -->
          <form method="GET" action="planning.php" class="week-jump-controls">
            <?php if ($selectedRessourceId > 0): ?>
              <input type="hidden" name="ress_id" value="<?= (int) $selectedRessourceId ?>" />
            <?php endif; ?>
            <?php if ($searchQuery !== ''): ?>
              <input type="hidden" name="q" value="<?= htmlspecialchars($searchQuery) ?>" />
            <?php endif; ?>
          
            <a class="btn-week-jump" href="<?= htmlspecialchars($todayUrl) ?>">Aujourd'hui</a>
          
            <label class="date-jump-label" for="dateJumpInput">
              <span>Aller à une date :</span>
              <input
                type="date"
                id="dateJumpInput"
                name="week_start"
                value="<?= htmlspecialchars($activeMonday) ?>"
              />
            </label>
            <button type="submit" class="btn-week-jump">Aller</button>
          </form>

        </div>

        <!-- calendrier -->
        <div class="calendar-scroll">

          <!-- En-Tete -->
          <div class="calendar-head">
            <div class="hours-heading">Heures</div>
              <?php foreach ($days as $day): ?>
              <div class="day-heading">
                <b><?= htmlspecialchars($day['label']) ?></b>
                <span><?= htmlspecialchars($day['date']) ?></span>
              </div>
            <?php endforeach; ?>
          </div>

          <!-- Corp -->
          <div class="calendar-body">

            <!-- heures -->
            <div class="hours-column">
              <?php for ($h = 7; $h < 20; $h++): ?>
                <div><?= sprintf('%02d:00', $h) ?></div>
              <?php endfor; ?>
            </div>

            <!-- Affichage -->
            <div class="days-grid">
              <?php foreach ($days as $dayIndex => $day): ?>
                <div class="day-column" aria-label="<?= htmlspecialchars($day['label']) ?>">
                  <?php foreach ($bookings as $booking): ?>
                    <?php if ((int) $booking['day_index'] === $dayIndex): ?>
                      <div
                        class="booking <?= htmlspecialchars($booking['tone']) ?>"
                        style="top: <?= (float) $booking['top_px'] ?>px; height: <?= (float) $booking['height_px'] ?>px;"
                        title="<?= htmlspecialchars($booking['title'] . ' (' . $booking['time_label'] . ')') ?>"
                      >
                        <div>
                          <b><?= htmlspecialchars($booking['title']) ?></b>
                          <small><span class="fa">&#xf007;</span> <?= htmlspecialchars($booking['person']) ?></small>
                        </div>
                        <small class="booking-time">
                          <span class="fa">&#xf017;</span> <?= htmlspecialchars($booking['time_label']) ?>
                        </small>
                      </div>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </div>
              <?php endforeach; ?>

              <?php if (empty($bookings)): ?>
                <p class="empty-week">Aucun créneau réservé pour cette ressource sur cette semaine.</p>
              <?php endif; ?>
            </div>
            
          </div>
        </div>

        <div class="week-picker">
          <a
            href="<?= htmlspecialchars($prevWeekUrl) ?>"
            class="week-arrow fa"
            aria-label="Semaine précédente"
          >&#xf053;</a>

          <div class="week-list">
            <?php foreach ($weeks as $week): ?>
              <?php
                $isActive = ($week['start_date'] === $activeMonday);
                $weekUrl = build_planning_url([
                    'week_start' => $week['start_date'],
                    'ress_id'    => $selectedRessourceId > 0 ? $selectedRessourceId : null,
                    'q'          => $searchQuery !== '' ? $searchQuery : null,
                ]);
              ?>
              <a
                href="<?= htmlspecialchars($weekUrl) ?>"
                class="week-item <?= $isActive ? 'active' : '' ?>"
              >
                <span><?= htmlspecialchars($week['code']) ?> —</span>
                <b><?= htmlspecialchars($week['label']) ?></b>
              </a>
            <?php endforeach; ?>
          </div>

          <a
            href="<?= htmlspecialchars($nextWeekUrl) ?>"
            class="week-arrow fa"
            aria-label="Semaine suivante"
          >&#xf054;</a>
        </div>
      </section>
    </main>

    <footer class="planning-footer">
      <span class="fa">&#xf19c;</span>
      <b>Le Mans Université</b>
      <span>• Gestionnaire - Planning</span>
    </footer>
  </div>
</body>

</html>