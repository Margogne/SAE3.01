<?php
session_start();
require_once '../model/model.php';
require_once '../model/PlanningModel.php';

$pageData = init_planning_page($_GET, $_SESSION);
if ($pageData === null) {
    header('Location: ID.php');
    exit();
}

extract($pageData);
$activeMondayStr = $currentMonday->format('Y-m-d');
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Planning des Salles &amp; Équipements - Le Mans Université</title>
  <link rel="stylesheet" href="../style/PartGenStyle.css" />
  <link rel="stylesheet" href="../style/PlanningStyle.css" />
</head>

<body>
  <div class="app-shell">
    <!-- Header conservé tel quel -->
    <header class="site-header" id="siteHeader">
      <a class="brand" href="PartAcc.php" aria-label="Le Mans Université">
        <img src="../assets/img/logoLemansBlancSurBleu.png" alt="Le Mans Université" />
      </a>

      <nav class="main-nav" aria-label="Navigation principale">
        <a class="nav-link" href="#dashboard">Dashboard</a>
        <a class="nav-link" href="PartAcc.php">Expérimentations</a>
        <a class="nav-link active" href="planning.php">Ressources</a>
        <a class="nav-link" href="#messagerie">Messagerie</a>
      </nav>

      <div class="header-actions">
        <div class="menu-anchor">
          <button type="button" class="profile-button" aria-haspopup="menu">
            <span class="avatar"><?= htmlspecialchars($initials) ?></span>
            <span class="profile-copy">
              <strong><?= htmlspecialchars($userNom . ' ' . $userPrenom) ?></strong>
              <small><?= htmlspecialchars($userRole) ?></small>
            </span>
          </button>
        </div>
      </div>
    </header>

    <section class="title-strip">
      <div>
        <h1><span class="fa">&#xf073;</span> Planning des Salles &amp; Équipements</h1>
        <p>Consultez les horaires d'usage réservés et naviguez librement entre les semaines.</p>
      </div>
    </section>

    <main class="planning-main">
      <?php if ($dbError !== ''): ?>
        <div class="alert-error" role="alert">
          <?= htmlspecialchars($dbError) ?>
        </div>
      <?php endif; ?>

      <section class="planning-filters" aria-label="Recherche de ressources">
        <form method="GET" action="planning.php" class="resource-search-form" id="resourceSearchForm">
          <input type="hidden" name="week_start" id="weekStartInput" value="<?= htmlspecialchars($activeMondayStr) ?>" />
          <input type="hidden" name="ress_id" id="selectedRessId" value="<?= (int) $selectedRessourceId ?>" />

          <label class="resource-search-label" for="resourceSearchInput">
            <span class="sr-only">Rechercher une ressource</span>
            <span class="fa search-icon">&#xf002;</span>
            <input
              type="search"
              id="resourceSearchInput"
              name="q"
              value="<?= htmlspecialchars($searchQuery) ?>"
              placeholder="Rechercher une ressource (nom, Salle, Matériel, bâtiment, équipement...)"
              autocomplete="off"
            />
          </label>

          <button type="submit" class="btn-search-submit">Rechercher</button>
          <?php if ($searchQuery !== ''): ?>
            <a href="planning.php?week_start=<?= urlencode($activeMondayStr) ?>" class="btn-search-reset">Réinitialiser</a>
          <?php endif; ?>
        </form>

        <div class="resource-results-bar" id="resourceResultsInfo">
          <span id="resourceCountLabel">
            <?= count($filteredResources) ?> ressource(s) trouvée(s)
          </span>
        </div>

        <div class="resource-pills" id="resourceList" role="listbox" aria-label="Ressources correspondantes">
          <?php if (empty($filteredResources)): ?>
            <p class="no-resource-match">Aucune ressource ne correspond à cette recherche.</p>
          <?php else: ?>
            <?php foreach ($filteredResources as $ress): ?>
              <?php $isSelected = ((int) $ress['id_ress'] === (int) $selectedRessourceId); ?>
              <button
                type="button"
                class="resource-pill <?= $isSelected ? 'active' : '' ?>"
                data-ress-id="<?= (int) $ress['id_ress'] ?>"
                data-ress-name="<?= htmlspecialchars(mb_strtolower($ress['nom_ress'], 'UTF-8')) ?>"
                data-ress-desc="<?= htmlspecialchars(mb_strtolower($ress['desc_ress'], 'UTF-8')) ?>"
                data-ress-type="<?= htmlspecialchars(mb_strtolower($ress['categorie_detectee'], 'UTF-8')) ?>"
                aria-selected="<?= $isSelected ? 'true' : 'false' ?>"
              >
                <span class="resource-badge <?= htmlspecialchars($ress['categorie_classe']) ?>">
                  <?= htmlspecialchars($ress['categorie_detectee']) ?>
                </span>
                <strong class="resource-pill-title"><?= htmlspecialchars($ress['nom_ress']) ?></strong>
                <small class="resource-pill-desc"><?= htmlspecialchars($ress['desc_ress']) ?></small>
              </button>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </section>

      <section class="calendar-card" aria-label="Planning hebdomadaire">
        <div class="calendar-toolbar">
          <div class="selected-resource-summary">
            <span>Ressource affichée :</span>
            <strong id="activeResourceName">
              <?= $selectedRessource ? htmlspecialchars($selectedRessource['nom_ress']) : 'Aucune ressource sélectionnée' ?>
            </strong>
            <?php if ($selectedRessource): ?>
              <small id="activeResourceDesc"><?= htmlspecialchars($selectedRessource['desc_ress']) ?></small>
            <?php endif; ?>
          </div>

          <div class="week-jump-controls">
            <a
              class="btn-week-jump"
              href="planning.php?week_start=<?= urlencode($todayWeekStart) ?>&amp;ress_id=<?= (int) $selectedRessourceId ?>&amp;q=<?= urlencode($searchQuery) ?>"
            >Aujourd'hui</a>
            <label class="date-jump-label" for="dateJumpInput">
              <span>Aller à une date :</span>
              <input
                type="date"
                id="dateJumpInput"
                value="<?= htmlspecialchars($activeMondayStr) ?>"
              />
            </label>
          </div>
        </div>

        <div class="calendar-scroll">
          <div class="calendar-head">
            <div class="hours-heading">Heures</div>
            <?php foreach ($days as $day): ?>
              <div class="day-heading">
                <b><?= htmlspecialchars($day['label']) ?></b>
                <span><?= htmlspecialchars($day['date']) ?></span>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="calendar-body">
            <div class="hours-column">
              <?php for ($h = 7; $h < 20; $h++): ?>
                <div><?= sprintf('%02d:00', $h) ?></div>
              <?php endfor; ?>
            </div>

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
          <button
            type="button"
            class="week-arrow fa"
            id="prevWeekBtn"
            data-target-week="<?= htmlspecialchars($prevWeekStart) ?>"
            aria-label="Semaine précédente"
          >&#xf053;</button>

          <div class="week-list">
            <?php foreach ($weeks as $week): ?>
              <?php $isActive = ($week['start_date'] === $activeMondayStr); ?>
              <button
                type="button"
                class="week-item <?= $isActive ? 'active' : '' ?>"
                data-week-start="<?= htmlspecialchars($week['start_date']) ?>"
              >
                <span><?= htmlspecialchars($week['code']) ?> —</span>
                <b><?= htmlspecialchars($week['label']) ?></b>
              </button>
            <?php endforeach; ?>
          </div>

          <button
            type="button"
            class="week-arrow fa"
            id="nextWeekBtn"
            data-target-week="<?= htmlspecialchars($nextWeekStart) ?>"
            aria-label="Semaine suivante"
          >&#xf054;</button>
        </div>
      </section>
    </main>

    <footer class="planning-footer">
      <span class="fa">&#xf19c;</span>
      <b>Le Mans Université</b>
      <span>• Gestionnaire - Planning</span>
    </footer>
  </div>

  <script>
    (function () {
      const searchInput = document.getElementById('resourceSearchInput');
      const pills = Array.from(document.querySelectorAll('.resource-pill'));
      const countLabel = document.getElementById('resourceCountLabel');
      const selectedRessInput = document.getElementById('selectedRessId');
      const weekStartInput = document.getElementById('weekStartInput');
      const dateJumpInput = document.getElementById('dateJumpInput');
      const form = document.getElementById('resourceSearchForm');

      function normalizeText(str) {
        return (str || '')
          .toLowerCase()
          .normalize('NFD')
          .replace(/[\u0300-\u036f]/g, '')
          .trim();
      }

      function filterPillsLive() {
        if (!searchInput || pills.length === 0) return;
        const terms = normalizeText(searchInput.value).split(/\s+/).filter(Boolean);
        let visibleCount = 0;

        pills.forEach((pill) => {
          const hay = normalizeText(
            (pill.dataset.ressName || '') + ' ' +
            (pill.dataset.ressDesc || '') + ' ' +
            (pill.dataset.ressType || '')
          );
          const match = terms.every((term) => hay.includes(term));
          pill.style.display = match ? '' : 'none';
          if (match) visibleCount++;
        });

        if (countLabel) {
          countLabel.textContent = visibleCount + ' ressource(s) trouvée(s)';
        }
      }

      function goToWeek(weekStartDate) {
        if (!weekStartDate || !weekStartInput || !form) return;
        weekStartInput.value = weekStartDate;
        form.submit();
      }

      if (searchInput) {
        searchInput.addEventListener('input', filterPillsLive);
        filterPillsLive();
      }

      pills.forEach((pill) => {
        pill.addEventListener('click', () => {
          const ressId = pill.dataset.ressId;
          if (selectedRessInput && form && ressId) {
            selectedRessInput.value = ressId;
            form.submit();
          }
        });
      });

      document.querySelectorAll('.week-item').forEach((btn) => {
        btn.addEventListener('click', () => goToWeek(btn.dataset.weekStart));
      });

      const prevBtn = document.getElementById('prevWeekBtn');
      const nextBtn = document.getElementById('nextWeekBtn');
      if (prevBtn) {
        prevBtn.addEventListener('click', () => goToWeek(prevBtn.dataset.targetWeek));
      }
      if (nextBtn) {
        nextBtn.addEventListener('click', () => goToWeek(nextBtn.dataset.targetWeek));
      }

      if (dateJumpInput) {
        dateJumpInput.addEventListener('change', () => {
          if (dateJumpInput.value) {
            goToWeek(dateJumpInput.value);
          }
        });
      }
    })();
  </script>
</body>

</html>