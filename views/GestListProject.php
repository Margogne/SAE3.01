<?php
session_start();
require_once '../model/model.php';

$pageData = init_gest_list_project_page($_GET, $_SESSION);
if ($pageData === null) {
    header('Location: ID.php');
    exit();
}

extract($pageData);
$resetUrl = strtok($_SERVER['REQUEST_URI'], '?') ?: 'GestListProject.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Page participant - Le Mans Université</title>
  <link rel="stylesheet" href="../style/PartGenStyle.css" />
  <link rel="stylesheet" href="../style/GestProject.css" />
</head>

<body>
  <div class="app-shell">

    <!-- HEADER DE LA PAGE -->
    <header class="site-header" id="siteHeader">

      <!-- LOGO LEMANS // retour page 1 -->
      <a class="brand" href="PartAcc.php" aria-label="Le Mans Université">
        <img src="../assets/img/logoLemansBlancSurBleu.png" alt="Le Mans Université" />
      </a>

      <!-- Menu de Navigation -->
      <nav class="main-nav" aria-label="Navigation principale">
        <a class="nav-link active" href="GestListProject.php">Projets</a>
        <a class="nav-link" href="GestDashboard.php">Dashboard</a>
        <a class="nav-link" href="GestDashboard.php">Ressources</a>
        <a class="nav-link" href="PartMess.php">Messagerie</a>
      </nav>

      <!-- zone utilisateur -->
      <div class="header-actions">

        <!-- Langues -->
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

        <!-- Notification -->
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
              <p><strong>#CE MENU EST FACTICE</strong></p>
            </div>
          </div>
        </div>

        <!-- Bloc USER -->
        <div class="menu-anchor profile-anchor">

          <!-- affichage User -->
          <button type="button" class="profile-button" aria-haspopup="menu">
            <span class="avatar"><?= htmlspecialchars($initials) ?></span>
            <span class="profile-copy">
              <strong><?= htmlspecialchars($userNom . '.' . $userPrenom) ?></strong>
              <small><?= htmlspecialchars($userRole) ?></small>
            </span>
            <span class="fa profile-chevron">&#xf078;</span>
          </button>

          <!-- affichage User In List -->
          <div class="dropdown profile-menu" role="menu" style="display: none;">
            <div class="profile-menu-heading">
              <span class="avatar dark"><?= htmlspecialchars($initials) ?></span>
              <span>
                <strong><?= htmlspecialchars($userNom . ' ' . $userPrenom) ?></strong>
                <small><?= htmlspecialchars($userRole) ?></small>
              </span>
            </div>

            <!-- Bouton de deconnexion -->
            <a href="../controller.php?action=logout" class="dropdown-item danger" role="menuitem"><span
                class="fa">&#xf2f5;</span> Se déconnecter
            </a>
            
          </div>
        </div>
      </div>
    </header>

    <!-- Bandeau Bleu, Haut de page -->
    <section class="page-heading" id="projets">
        <div>
            <h1 class="page-title">
              <img src="../assets/img/SymbolFichier.png" alt="" />
              Projets du Laboratoire
            </h1>
        </div>
    </section>

    <!-- Corp de Page -->
    <main class="main-content">

        <?php if ($messageSucces !== ''): ?>
            <div class="alert-success">
                <i class="fas fa-check-circle" style="margin-right:6px;"></i>
                <?= $messageSucces ?>
            </div>
        <?php endif; ?>

        <!-- Zone de recherche et filtres (GET) -->
        <section class="filter-box">
            <h2 class="filter-box-title">
                <i class="fas fa-filter"></i>
                Filtrer &amp; Rechercher
            </h2>

            <form method="GET" action="">
                <div class="filter-grid">
                    <label class="filter-field">
                        <span>Mot-clé / Titre</span>
                        <span class="input-icon-wrapper">
                            <i class="fas fa-search"></i>
                            <input
                                type="text"
                                name="search"
                                value="<?= htmlspecialchars($query) ?>"
                                placeholder="Rechercher un projet, un mot-clé..."
                                class="with-icon"
                            />
                        </span>
                    </label>

                    <label class="filter-field">
                        <span>Domaine de recherche</span>
                        <select name="domain">
                            <?php foreach (['Tous les domaines', 'Robotique', 'Réalité virtuelle', 'Langage'] as $optDomain): ?>
                                <option value="<?= htmlspecialchars($optDomain) ?>" <?= $domain === $optDomain ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($optDomain) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>

                    <label class="filter-field">
                        <span>Statut</span>
                        <select name="status">
                            <?php foreach (['Tous les statuts', 'En gestion', 'Tests actifs'] as $optStatus): ?>
                                <option value="<?= htmlspecialchars($optStatus) ?>" <?= $status === $optStatus ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($optStatus) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>

                    <label class="filter-field">
                        <span>Mes projets</span>
                        <span class="checkbox-box">
                            <input type="checkbox" name="mine" value="1" <?= $mineOnly ? 'checked' : '' ?> />
                            Afficher uniquement les miens
                        </span>
                    </label>
                </div>

                <div class="filter-actions">
                    <a href="<?= htmlspecialchars(strtok($_SERVER['REQUEST_URI'], '?')) ?>" class="btn-filter-reset">
                        Réinitialiser
                    </a>
                    <button type="submit" class="btn-filter-submit">
                        Appliquer les filtres
                    </button>
                </div>
            </form>
        </section>

        <!-- Liste des projets filtrés -->
        <?php if (!empty($filteredProjects)): ?>
            <section class="projects-grid" aria-live="polite">
                <?php foreach ($filteredProjects as $project): ?>
                    <article class="project-card">
                        <div
                            class="project-cover"
                            style="background-image: url('<?= htmlspecialchars($project['image']) ?>'); background-position: <?= htmlspecialchars($project['imagePosition']) ?>;"
                        >
                            <span class="status-badge">
                                <span class="status-dot" style="color: <?= htmlspecialchars($project['statusColor']) ?>;">●</span>
                                <?= htmlspecialchars($project['status']) ?>
                            </span>
                        </div>

                        <div class="project-body">
                            <p class="project-category"><?= htmlspecialchars($project['category']) ?></p>
                            <h3 class="project-title"><?= htmlspecialchars($project['title']) ?></h3>
                            <p class="project-desc"><?= htmlspecialchars($project['description']) ?></p>

                            <div class="project-footer">
                                <span class="project-owner">
                                    <i class="fas fa-user-tie"></i>
                                    <?= htmlspecialchars($project['owner']) ?>
                                </span>
                                <a  href="" class="btn-card">
                                    Voir la fiche <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php else: ?>
            <div class="empty-state">
                Aucun projet ne correspond à ces critères.
            </div>
        <?php endif; ?>

    </main>

    <!-- Pied de page -->
    <footer>
      <span class="fa"></span>
      <p>
        <strong>Le Mans Université</strong> • <?= htmlspecialchars($userRole ?? 'Utilisateur') ?>
        - Listing des projets
      </p>
    </footer>
  </div>
</body>

</html>