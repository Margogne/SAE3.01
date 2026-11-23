<?php
require("../controller/ECListProjetController.php");

?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Page participant - Le Mans Université</title>
  <link rel="stylesheet" href="../style/Partstyle.css" />
</head>

<body>
  <div class="app-shell">
    <?php
    include '../model/ECHeaderModel.php';
    ?>
    <section class="page-heading" id="projets">
      <h1>
        <img src="../assets/img/SymbolFichier.png" alt="" />
        Projet
      </h1>
    </section>

    <main>
      <section class="filter-panel" aria-label="Recherche de projets">
        <h2>
          <span class="fa"></span>
          Filtrer & Rechercher
        </h2>

        <form method="GET" action="" class="filter-form-centered">
          <label class="field search-container-single">
            <span>Mot-clé / Titre</span>
            <span class="search-field">
              <span class="fa search-icon"></span>
              <input type="search" name="search" value="" placeholder="Rechercher un projet, un mot-clé..." />
            </span>
          </label>

          <div class="filter-actions-single">
            <button type="submit" class="btn-filter-submit">
              <span class="fa"></span> Rechercher
            </button>
          </div>
        </form>
      </section>

      <section class="project-grid" id="projectGrid" aria-live="polite">
        <?php if (empty($projects)): ?>
          <div class="no-results" style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #9ba8bd;">
            <span class="fa" style="font-size: 32px; margin-bottom: 12px; display: block;"></span>
            <strong>Aucun projet trouvé</strong>
            <p>Vous n'êtes rattaché à aucun projet pour le moment.</p>
          </div>
        <?php else: ?>
          <?php foreach ($projects as $project): ?> 
            <article class="project-card">

              <div class="project-image"
                style="display: flex; align-items: center; justify-content: center; text-align: center; padding: 12px;">
                <?php if (!empty($project['image'])): ?>
                  <img src="<?= htmlspecialchars($project['image']) ?>"
                    alt="<?= htmlspecialchars($project['nom_proj'] ?? '') ?>" />
                <?php else: ?>
                  <img src="<?= htmlspecialchars(!empty($project['image']) ? $project['image'] : '../assets/img/imageProjetPlaceHolder.webp') ?>" alt="<?= htmlspecialchars($project['nom_proj'] ?? 'Projet') ?>" />
              </div>
              <?php endif; ?>

              <div class="project-content">
                <?php if (!empty($project['category'])): ?>
                  <p class="project-category"><?= htmlspecialchars($project['category']) ?></p>
                <?php endif; ?>
                <h3><?= htmlspecialchars($project['nom_proj'] ?? '') ?></h3>
                <p class="project-description"><?= htmlspecialchars($project['desc_proj'] ?? '') ?></p>
                <div class="project-footer">
                  <a href="PartExpSess.php?id=<?= urlencode((string) ($project['id_proj'] ?? '')) ?>"
                    class="view-details-btn" style="text-decoration: none;">
                    Voir la fiche <span class="fa"></span>
                  </a>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </section>
    </main>

    <footer>
      <span class="fa"></span>
      <p>
        <strong>Le Mans Université</strong> • <?= htmlspecialchars($userRole ?? 'Utilisateur') ?> - Catalogue des
        expérimentations
      </p>
    </footer>
  </div>
</body>

</html>