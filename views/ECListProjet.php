<?php
require("../controller/ECController.php");

?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Page participant - Le Mans Université</title>
  <link rel="stylesheet" href="../Style/ECListProjet.css" />
  <link rel="stylesheet" href="../style/ECheader.css" />

</head>

<body>
  <div class="app-shell">
    <header class="site-header">

      <button class="brand" aria-label="Retour au tableau de bord">
        <a href="ECDashboard.php"><img src="../assets/img/logoLemansBlancSurBleu.svg" alt="Le Mans Université" /></a>
      </button>

      <nav class="main-navigation" aria-label="Navigation principale">
        <a href="ECDashboard.php"><button class="nav-link">Dashboard</button></a>
        <a href="ECListProjet.php"><button class="nav-link active">Projets</button></a>
        <a href="ECRessources.php"><button class="nav-link">Ressources</button></a>
        <a href="ECMess.php"><button class="nav-link">Messagerie</button></a>
      </nav>

      <div class="header-actions">
        <!-- langue -->
        <div class="menu-anchor language-anchor">
          <button type="button" class="language-trigger" aria-haspopup="menu">
            <img src="../assets/img/Globe.svg" alt="" />
            <span>FR</span>
            <img class="small-chevron" src="../assets/img/Vector.svg" alt="" />
          </button>
          <div class="dropdown language-menu" role="menu" style="display: none;">
            <a href="?&lang=FR" class="dropdown-item selected" role="menuitem">FR <span class="fa">&#xf00c;</span></a>
            <a href="?&lang=EN" class="dropdown-item" role="menuitem">EN</a>
            <a href="?&lang=ES" class="dropdown-item" role="menuitem">ES</a>
          </div>
        </div>

        <!-- notifications -->
        <div class="menu-anchor">
          <button type="button" class="icon-trigger" aria-label="Notifications" aria-haspopup="dialog">
            <img src="../assets/img/bell.svg" alt="" />
          </button>
          <div class="dropdown notification-menu" role="dialog" aria-label="Notifications" style="display: none;">
            <h2>Notifications</h2>
            <div class="empty-notifications">
              <span class="notification-illustration">
                <span class="fa" style="font-size: 24px; color: #9ba8bd;">&#xf0a2;</span>
              </span>
              <strong>Aucune notification</strong>
              <p>Vous êtes à jour. Les nouvelles activités apparaîtront ici.</p>
            </div>
          </div>
        </div>

        <div class="menu-anchor profile-anchor">
          <button type="button" class="profile-button" aria-haspopup="menu">
            <span class="avatar"><?= htmlspecialchars($initials) ?></span>
            <span class="profile-copy">
              <strong><?= htmlspecialchars($userNom . '.' . $userPrenom) ?></strong>
              <small><?= htmlspecialchars($userRole) ?></small>
            </span>
            <img class="profile-chevron" src="../assets/img/Vector.svg" alt="" />
          </button>
          <div class="dropdown profile-menu" role="menu" style="display: none;">
            <div class="profile-menu-heading">
              <span class="avatar dark"><?= htmlspecialchars($initials) ?></span>
              <span>
                <strong><?= htmlspecialchars($userNom . ' ' . $userPrenom) ?></strong>
                <small><?= htmlspecialchars($userRole) ?></small>
              </span>
            </div>
            <a href="../controller.php?action=logout" class="dropdown-item danger" role="menuitem"><span class="fa">&#xf2f5;</span>Se déconnecter</a>
          </div>
        </div>

      </div>
    </header>

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
            <?php if (trim($_GET["search"] ?? '') !== '') : ?>
              <a href="ECListProjet.php" class="btn-filter-reset"><span>Réinitialiser</span></a>
            <?php endif; ?>
          </div>
        </form>
      </section>

      <section class="project-grid" id="projectGrid" aria-live="polite">
        <!--<?php if (empty(get_projets_from_util($_SESSION['user_id']))) : ?>
          <div class="no-results" style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #9ba8bd;">
            <span class="fa" style="font-size: 32px; margin-bottom: 12px; display: block;"></span>
            <strong>Aucun projet trouvé</strong>
            <p>Vous n'êtes rattaché à aucun projet pour le moment.</p>
          </div>
        <?php else : ?>
          <?php if (isset($_Get['search'])) {
                foreach (get_projets_with_search($_GET['search']) as $project) : ?>
            <article class="project-card">

              <div class="project-image"
                style="display: flex; align-items: center; justify-content: center; text-align: center; padding: 12px;">
                <?php if (!empty($project['image'])) : ?>
                  <img src="<?= htmlspecialchars($project['image']) ?>"
                    alt="<?= htmlspecialchars($project['nom_proj'] ?? '') ?>" />
                <?php else : ?>
                  <img
                    src="<?= htmlspecialchars(!empty($project['image']) ? $project['image'] : '../assets/img/imageProjetPlaceHolder.webp') ?>"
                    alt="<?= htmlspecialchars($project['nom_proj'] ?? 'Projet') ?>" />
                </div>
              <?php endif; ?>

              <div class="project-content">
                <?php if (!empty($project['category'])) : ?>
                  <p class="project-category"><?= htmlspecialchars($project['category']) ?></p>
                <?php endif; ?>
                <h3><?= htmlspecialchars($project['nom_proj'] ?? '') ?></h3>
                <p class="project-description"><?= htmlspecialchars($project['desc_proj'] ?? '') ?></p>
                <div class="project-footer">
                  <a href="ECFicheProj.php?id_proj=<?= urlencode((string) ($project['id_proj'] ?? '')) ?>"
                    class="view-details-btn" style="text-decoration: none;">
                    Voir la fiche <span class="fa"></span>
                  </a>
                </div>
              </div>
            </article>
          <?php endforeach;
              } else {
          ?>
            <?php foreach (get_projets_from_util($_SESSION['user_id']) as $project) : ?>
            <article class="project-card">

              <div class="project-image"
                style="display: flex; align-items: center; justify-content: center; text-align: center; padding: 12px;">
                <?php if (!empty($project['image'])) : ?>
                  <img src="<?= htmlspecialchars($project['image']) ?>"
                    alt="<?= htmlspecialchars($project['nom_proj'] ?? '') ?>" />
                <?php else : ?>
                  <img
                    src="<?= htmlspecialchars(!empty($project['image']) ? $project['image'] : '../assets/img/imageProjetPlaceHolder.webp') ?>"
                    alt="<?= htmlspecialchars($project['nom_proj'] ?? 'Projet') ?>" />
                </div>
              <?php endif; ?>

              <div class="project-content">
                <?php if (!empty($project['category'])) : ?>
                  <p class="project-category"><?= htmlspecialchars($project['category']) ?></p>
                <?php endif; ?>
                <h3><?= htmlspecialchars($project['nom_proj'] ?? '') ?></h3>
                <p class="project-description"><?= htmlspecialchars($project['desc_proj'] ?? '') ?></p>
                <div class="project-footer">
                  <a href="ECFicheProj.php?id_proj=<?= urlencode((string) ($project['id_proj'] ?? '')) ?>"
                    class="view-details-btn" style="text-decoration: none;">
                    Voir la fiche <span class="fa"></span>
                  </a>
                </div>
              </div>
            </article>
            <?php endforeach; ?>
        <?php }
            endif; ?>-->
        <?php
        $projets = !empty($_GET['search'])
          ? get_projets_with_search(trim($_GET['search']))
          : get_projets_from_util((int) ($_SESSION['user_id'] ?? 0));
        ?>

        <?php if (empty($projets)) : ?>
          <div class="no-results" style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #9ba8bd;">
            <span class="fa" style="font-size: 32px; margin-bottom: 12px; display: block;"></span>
            <strong>Aucun projet trouvé</strong>
            <p>Vous n'êtes rattaché à aucun projet pour le moment.</p>
          </div>
        <?php else : ?>
          <?php foreach ($projets as $project) : ?>
            <article class="project-card">

              <div class="project-image" style="display: flex; align-items: center; justify-content: center; text-align: center; padding: 12px;">
                <img src="<?= htmlspecialchars(!empty($project['image']) ? $project['image'] : '../assets/img/imageProjetPlaceHolder.webp') ?>" alt="<?= htmlspecialchars($project['nom_proj'] ?? 'Projet') ?>" />
              </div>

              <div class="project-content">
                <?php if (!empty($project['category'])) : ?>
                  <p class="project-category"><?= htmlspecialchars($project['category']) ?></p>
                <?php endif; ?>
                <h3><?= htmlspecialchars($project['nom_proj'] ?? '') ?></h3>
                <p class="project-description"><?= htmlspecialchars($project['desc_proj'] ?? '') ?></p>
                <div class="project-footer">
                  <a href="ECFicheProj.php?id_proj=<?= urlencode((string) ($project['id_proj'] ?? '')) ?>" class="view-details-btn" style="text-decoration: none;">
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