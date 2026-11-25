<?php
include "../controller/ECDashboardController.php";
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Espace Enseignant-Chercheur · LIUM - Le Mans Université</title>
    <link rel="stylesheet" href="../style/ECDashboard.css" />
    <link rel="stylesheet" href="../style/ECheader.css" />
</head>

<body>
    <div class="app-shell">

        <header class="site-header">

            <button class="brand" aria-label="Retour au tableau de bord">
                <img src="../assets/img/logoLemansBlancSurBleu.svg" alt="Le Mans Université" />
            </button>

            <nav class="main-navigation" aria-label="Navigation principale">
                <a href="ECDashboard.php"><button class="nav-link active">Dashboard</button></a>
                <a href="ECListProjet.php"><button class="nav-link">Projets</button></a>
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
                        <a href="?id=<?= $idExp ?>&lang=FR" class="dropdown-item selected" role="menuitem">FR <span
                                class="fa">&#xf00c;</span></a>
                        <a href="?id=<?= $idExp ?>&lang=EN" class="dropdown-item" role="menuitem">EN</a>
                        <a href="?id=<?= $idExp ?>&lang=ES" class="dropdown-item" role="menuitem">ES</a>
                    </div>
                </div>

                <!-- notifications -->
                <div class="menu-anchor">
                    <button type="button" class="icon-trigger" aria-label="Notifications"
                        aria-haspopup="dialog">
                        <img src="../assets/img/bell.svg" alt="" />
                    </button>
                    <div class="dropdown notification-menu" role="dialog" aria-label="Notifications"
                        style="display: none;">
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
                        <a href="../controller.php?action=logout" class="dropdown-item danger" role="menuitem"><span
                                class="fa">&#xf2f5;</span>Se déconnecter</a>
                    </div>
                </div>

            </div>
        </header>
        <main class="dashboard-content">
            <section class="welcome-banner">
                <div class="banner-orbit" aria-hidden="true"></div>
                <div class="welcome-copy">
                    <p class="eyebrow">Espace Enseignant-Chercheur</p>
                    <h1>Bonjour <?php echo mb_substr($userPrenom, 0, 1) . '. ' . $userNom ?></h1>
                    <p>
                        Pilotez vos expérimentations, suivez les projets de recherche et retrouvez les
                        ressources partagées du laboratoire.
                    </p>
                </div>
            </section>

            <section class="overview-section" aria-labelledby="overview-heading">
                <div class="section-heading">
                    <h2 id="overview-heading">Vue d’ensemble</h2>
                    <!--<p>Mis à jour aujourd’hui à <?php echo date('H:i'); ?></p>-->
                </div>

                <div class="overview-grid">
                    <article class="overview-card">
                        <div class="card-top">
                            <span class="card-icon">
                                <img src="../assets/img/ProjetsActifs.svg" alt="" />
                            </span>
                            <strong class="card-value"><?php echo get_nb_proj() ?></strong>
                        </div>
                        <h3>Projets actifs</h3>
                        <!--<p>2 échéances cette semaine</p>-->
                        <div class="card-actions">
                            <a href="ECCreateProject.php"><button>Proposer un projet</button></a>
                            <a href="ECListProjet.php"><button>Voir mes projets</button></a>
                        </div>
                    </article>

                    <article class="overview-card">
                        <div class="card-top">
                            <span class="card-icon">
                                <img src="../assets/img/Expérimentations.svg" alt="" />
                            </span>
                            <strong class="card-value"><?php echo get_nb_experimentations() ?></strong>
                        </div>
                        <h3>Expérimentations</h3>
                        <!--<p>8 en cours · 4 à valider</p>-->
                        <div class="card-actions">
                            <a href="ECCreateExp.php"><button>Créer une expérimentation</button></a>
                        </div>
                    </article>

                    <article class="overview-card">
                        <div class="card-top">
                            <span class="card-icon">
                                <img src="../assets/img/Participants.svg" alt="" />
                            </span>
                            <strong class="card-value"><?php echo get_nb_participants() ?></strong>
                        </div>
                        <h3>Participants</h3>
                        <!--<p>+5 sur les 30 derniers jours</p>-->
                    </article>

                    <article class="overview-card">
                        <div class="card-top">
                            <span class="card-icon">
                                <img src="../assets/img/RessourcesPartagées.svg" alt="" />
                            </span>
                            <strong class="card-value"><?php echo get_nb_ressources() ?></strong>
                        </div>
                        <h3>Ressources partagées</h3>
                        <!--<p>3 nouveaux documents</p>-->
                    </article>
                </div>
            </section>

            <section class="activity-layout">
                <article class="recent-card">
                    <h2>Expérimentations récentes</h2>
                    <div class="experiment-list">
                        <?php foreach (get_experimentations() as $exp) { ?>
                            <div class="experiment-row">
                                <span class="status-marker green" aria-hidden="true"></span>
                                <div class="experiment-info">
                                    <h3><?php echo $exp['titre_exp']; ?></h3>
                                    <p><?php $dateAjd = new DateTimeImmutable();
                                    if (new DateTimeImmutable($exp['datefin_exp']) < $dateAjd) {
                                        echo "Terminée";
                                    } else {
                                        echo "En cours";
                                    }
                                    ?></p>
                                    <a href="ECFicheExp.php?id_exp=<?= urlencode((string) ($exp['id_exp'] ?? '')) ?>"
                                        style="color: inherit; text-decoration: none;"><button>
                                            <span>Voir la fiche</span>
                                            <span class="font-awesome" aria-hidden="true">&#xf061;</span>
                                        </button></a>
                                </div>
                                <time><?php echo $exp['datefin_exp']; ?></time>
                            </div>
                        <?php } ?>
                    </div>
                </article>

                <aside class="deadline-card">
                    <h2>Prochaine échéance</h2>
                    <div class="deadline-date">
                        <!--<strong>28</strong>
                        <span>Septembre 2026</span>-->
                        <?php echo get_prochaine_echeance() ?>
                    </div>
                    <!--<p>Clôture de la collecte · Projet ORION</p>-->
                </aside>
            </section>
        </main>

        <footer>
            <span class="font-awesome university-icon" aria-hidden="true">&#xf19c;</span>
            <p>
                <strong>Le Mans Université</strong> <span>• Dashboard</span>
            </p>
        </footer>
    </div>
</body>

</html>