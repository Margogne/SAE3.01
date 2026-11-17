<?php
session_start();

require_once '../model/model.php';

// Sécurité : redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
  header("Location: ID.php");
  exit();
}

// 1. RÉCUPÉRATION DE TOUS LES PROJETS DEPUIS LA BASE DE DONNÉES
$ExperimentationsData = Get_Exp_WithSess();

// 2. RÉCUPÉRATION DES FILTRES ET PARAMÈTRES D'URL ($_GET)
$searchQuery = trim($_GET['search'] ?? '');
$modalProjectId = isset($_GET['projet_id']) ? (int) $_GET['projet_id'] : null;

// 3. FILTRAGE DES PROJETS EN PHP
$filteredProjects = array_filter($ExperimentationsData, function ($project) use ($searchQuery) {
  $searchable = mb_strtolower($project['title'] . ' ' . $project['category'] . ' ' . $project['description'], 'UTF-8');
  return ($searchQuery === '') || (mb_strpos($searchable, mb_strtolower($searchQuery, 'UTF-8')) !== false);
});

// =========================================================================
// 4. RECHERCHE DE L'EXPÉRIMENTATION SÉLECTIONNÉE POUR LA MODALE
// =========================================================================
$selectedProject = null;
if ($modalProjectId !== null) {
  foreach ($ExperimentationsData as $p) {
    if ($p['id'] === $modalProjectId) {
      $selectedProject = $p;
      break;
    }
  }
}

// 5. PRÉPARATION DES INFOS UTILISATEUR EN SESSION
$userNom = $_SESSION['user_nom'] ?? 'Utilisateur';
$userPrenom = $_SESSION['user_prenom'] ?? '';
$userRole = $_SESSION['user_role'] ?? 'Participant';

$initiales = strtoupper(mb_substr($userPrenom, 0, 1) . mb_substr($userNom, 0, 1));
if (empty($initiales)) {
  $initiales = 'U';
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
// ============================================================================
// HTML DE LA PAGE
// ============================================================================
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Page participant - Le Mans Université</title>

  <!-- Lien vers la feuille de style externe -->
  <link rel="stylesheet" href="../style/Partstyle.css" />
</head>

<body>

  <!-- DIV principale de l'application -->
  <div class="app-shell" data-node-id="281:2782">

    <!-- HEADER -- Bandeau navigation -->
    <header class="site-header" id="siteHeader" data-node-id="281:2908">
      <a class="brand" href="PartAcc.php" aria-label="Le Mans Université">
        <img src="../assets/img/logoLemansBlancSurBleu.png" alt="Le Mans Université" />
      </a>

      <!-- Navigation principale -->
      <nav class="main-nav" aria-label="Navigation principale">
        <a class="nav-link" href="#dashboard">Dashboard</a>
        <a class="nav-link active" href="PartAcc.php">Expérimentations</a>
        <a class="nav-link" href="#ressources">Ressources</a>
        <a class="nav-link" href="#messagerie">Messagerie</a>
      </nav>

      <!-- Actions de l'en-tête (Langue, Notifications, Profil) -->
      <div class="header-actions">

        <!-- Sélecteur de langue (fonctionne au survol/clic via CSS) -->
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

        <!-- Menu des notifications -->
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

        <!-- Menu du profil utilisateur -->
        <div class="menu-anchor profile-anchor">
          <button type="button" class="profile-button" aria-haspopup="menu">
            <span class="avatar"><?= htmlspecialchars($initiales) ?></span>
            <span class="profile-copy">
              <strong><?= htmlspecialchars($userNom . '.' . $userPrenom) ?></strong>
              <small><?= htmlspecialchars($userRole) ?></small>
            </span>
            <span class="fa profile-chevron">&#xf078;</span>
          </button>
          <div class="dropdown profile-menu" role="menu" style="display: none;">
            <div class="profile-menu-heading">
              <span class="avatar dark"><?= htmlspecialchars($initiales) ?></span>
              <span>
                <strong><?= htmlspecialchars($userNom . ' ' . $userPrenom) ?></strong>
                <small><?= htmlspecialchars($userRole) ?></small>
              </span>
            </div>
            <a href="#profil" class="dropdown-item" role="menuitem"><span class="fa">&#xf007;</span> Mon profil</a>
            <a href="#parametres" class="dropdown-item" role="menuitem"><span class="fa">&#xf013;</span> Paramètres</a>
            <a href="../controller.php?action=logout" class="dropdown-item danger" role="menuitem"><span
                class="fa">&#xf2f5;</span> Se déconnecter</a>
          </div>
        </div>

      </div>
    </header>

    <!-- En-tête de la page -->
    <section class="page-heading" id="projets">
      <h1>
        <img src="../assets/img/SymbolFichier.png" alt=""  />
        Expérimentations
      </h1>
    </section>

    <!-- Corps principal -->
    <main>
      <!-- Panneau de filtres et recherche  -->
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
              <input type="search" name="search" value="<?= htmlspecialchars($searchQuery) ?>"
                placeholder="Rechercher un projet, un mot-clé..." />
            </span>
          </label>

          <div class="filter-actions-single">
            <button type="submit" class="btn-filter-submit">
              <span class="fa">&#xf002;</span> Rechercher
            </button>
            <?php if ($searchQuery !== ''): ?>
              <a href="PartAcc.php" class="btn-filter-reset">Réinitialiser</a>
            <?php endif; ?>
          </div>
        </form>
      </section>

      <!-- Grille des projets générée en PHP -->
      <section class="project-grid" id="projectGrid" aria-live="polite">
        <?php if (empty($filteredProjects)): ?>
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
          <?php foreach ($filteredProjects as $project): ?>
            <article class="project-card">
              <div class="project-image">
                <img src="<?= htmlspecialchars($project['image']) ?>" alt="<?= htmlspecialchars($project['title']) ?>" />
              </div>
              <div class="project-content">
                <p class="project-category"><?= htmlspecialchars($project['category']) ?></p>
                <h3><?= htmlspecialchars($project['title']) ?></h3>
                <p class="project-description"><?= htmlspecialchars($project['description']) ?></p>
                <div class="project-footer">
                  <!-- Lien PHP qui ouvre la modale en ajoutant ?projet_id=X tout en gardant les filtres -->
                  <a href="<?= htmlspecialchars(buildFilterQuery(['projet_id' => $project['id']])) ?>"
                    class="view-details-btn" style="text-decoration: none;">
                    Voir la fiche <span class="fa">&#xf061;</span>
                  </a>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </section>
    </main>

    <!-- Pied de page -->
    <footer>
      <span class="fa">&#xf19d;</span>
      <p>
        <strong>Le Mans Université</strong> • <?= htmlspecialchars($userRole) ?> - Catalogue des expérimentations
      </p>
    </footer>

    <!-- /!\il faudra le remplcé par une page projet (maybe)/!\ -->
    <!-- Modale de détail d'un projet -->
    <?php if ($selectedProject !== null): ?>
      <div class="modal-backdrop" id="projectModal" role="presentation" style="display: flex;">
        <section class="project-modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
          <a href="<?= htmlspecialchars(buildFilterQuery()) ?>" class="modal-close" aria-label="Fermer"
            style="text-decoration: none;">×</a>
          <img src="<?= htmlspecialchars($selectedProject['image']) ?>"
            alt="<?= htmlspecialchars($selectedProject['title']) ?>" />
          <div>
            <p class="project-category"><?= htmlspecialchars($selectedProject['category']) ?></p>
            <h2 id="modalTitle"><?= htmlspecialchars($selectedProject['title']) ?></h2>
            <p><?= htmlspecialchars($selectedProject['description']) ?></p>
            <span class="owner">
              <span class="fa">&#xf2bc;</span>
              <span><?= htmlspecialchars($selectedProject['owner']) ?></span>
            </span>
          </div>
        </section>
      </div>
    <?php endif; ?>

  </div>

</body>

</html>