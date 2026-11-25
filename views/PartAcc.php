<?php
session_start();
require_once '../model/model.php';

$pageData = init_part_acc_page($_GET['search'] ?? '', $_SESSION);
if ($pageData === null) {
  header('Location: ID.php');
  exit();
}

extract($pageData);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Page participant - Le Mans Université</title>
  <link rel="stylesheet" href="../style/PartGenStyle.css" />
</head>

<body>
  <div class="app-shell" data-node-id="281:2782">

    <header class="site-header" id="siteHeader" data-node-id="281:2908">
      <a class="brand" href="PartAcc.php" aria-label="Le Mans Université">
        <img src="../assets/img/logoLemansBlancSurBleu.png" alt="Le Mans Université" />
      </a>

      <nav class="main-nav" aria-label="Navigation principale">
        <a class="nav-link active" href="PartAcc.php">Accueil</a>
        <a class="nav-link" href="#Experimentation">Expérimentations</a>
        <a class="nav-link" href="#ressources">Ressources</a>
        <a class="nav-link" href="PartMess.php">Messagerie</a>
      </nav>

      <div class="header-actions">
        <div class="menu-anchor language-anchor">
          <button type="button" class="header-button language-button" aria-haspopup="menu">
            <span>FR</span>
            <span class="fa" style="font-size: 9px;">&#xf078;</span>
          </button>
          <div class="dropdown language-menu" role="menu" style="display: none;">
            <a href="?lang=FR" class="dropdown-item selected" role="menuitem">FR <span class="fa">&#xf00c;</span></a>
            <a href="?lang=EN" class="dropdown-item" role="menuitem">EN</a>
            <a href="?lang=ES" class="dropdown-item" role="menuitem">ES</a>
          </div>
        </div>

        <div class="menu-anchor">
          <button type="button" class="header-button icon-button" aria-label="Notifications" aria-haspopup="dialog">
            <span class="fa">&#xf0f3;</span>
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
            <span class="fa profile-chevron">&#xf078;</span>
          </button>
          <div class="dropdown profile-menu" role="menu" style="display: none;">
            <div class="profile-menu-heading">
              <span class="avatar dark"><?= htmlspecialchars($initials) ?></span>
              <span>
                <strong><?= htmlspecialchars($userNom . ' ' . $userPrenom) ?></strong>
                <small><?= htmlspecialchars($userRole) ?></small>
              </span>
            </div>
            <a href="PartProfil.php" class="dropdown-item" role="menuitem"><span class="fa">&#xf007;</span> Mon
              profil</a>
            <a href="#parametres" class="dropdown-item" role="menuitem"><span class="fa">&#xf013;</span> Paramètres</a>
            <a href="../controller.php?action=logout" class="dropdown-item danger" role="menuitem"><span
                class="fa">&#xf2f5;</span> Se déconnecter
            </a>
          </div>
        </div>
      </div>
    </header>

    <section class="page-heading" id="projets">
      <h1>
        <img src="../assets/img/SymbolFichier.png" alt="" />
        Expérimentations
      </h1>
    </section>

    <main>
      <section class="filter-panel" aria-label="Recherche de projets">
        <h2>
          <span class="fa">&#xf0b0;</span>
          Filtrer &amp; Rechercher
        </h2>

        <form method="GET" action="PartAcc.php" class="filter-form-centered">
          <label class="field search-container-single">
            <span>Mot-clé / Titre</span>
            <span class="search-field">
              <span class="fa search-icon">&#xf002;</span>
              <input type="search" name="search" value="<?= htmlspecialchars($search) ?>"
                placeholder="Rechercher un projet, un mot-clé..." />
            </span>
          </label>

          <div class="filter-actions-single">
            <button type="submit" class="btn-filter-submit">
              <span class="fa">&#xf002;</span> Rechercher
            </button>
            <?php if ($search !== ''): ?>
              <a href="PartAcc.php" class="btn-filter-reset">Réinitialiser</a>
            <?php endif; ?>
          </div>
        </form>
      </section>

      <section class="project-grid" id="projectGrid" aria-live="polite">
        <?php if (empty($projects)): ?>
          <div class="no-results" style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #9ba8bd;">
            <span class="fa" style="font-size: 32px; margin-bottom: 12px; display: block;">&#xf002;</span>
            <strong>Aucun projet trouvé</strong>
            <p>Modifiez vos critères de recherche pour afficher d’autres projets.</p>
            <a href="PartAcc.php"
              style="display: inline-block; margin-top: 12px; padding: 8px 16px; background: #c94a29; color: white; text-decoration: none; border-radius: 6px;">
              Réinitialiser les filtres
            </a>
          </div>
        <?php else: ?>
          <?php foreach ($projects as $project): ?>
            <article class="project-card">
              <div class="project-image">
                <img src="<?= htmlspecialchars($project['image']) ?>" alt="<?= htmlspecialchars($project['title']) ?>" />
              </div>
              <div class="project-content">
                <p class="project-category"><?= htmlspecialchars($project['category']) ?></p>
                <h3><?= htmlspecialchars($project['title']) ?></h3>
                <p class="project-description"><?= htmlspecialchars($project['description']) ?></p>
                <div class="project-footer">
                  <a href="PartExpSess.php?id=<?= urlencode((string) $project['id']) ?>" class="view-details-btn"
                    style="text-decoration: none;">
                    Voir la fiche <span class="fa">&#xf061;</span>
                  </a>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </section>
    </main>

    <footer>
      <span class="fa">&#xf19d;</span>
      <p>
        <strong>Le Mans Université</strong> • <?= htmlspecialchars($userRole) ?> - Catalogue des expérimentations
      </p>
    </footer>
  </div>
</body>

</html>