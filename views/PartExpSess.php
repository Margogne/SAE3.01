<?php
session_start();

// Sécurité : redirection si non connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ID.php");
    exit();
}

// Infos utilisateur en session (identique à PartAcc.php)
$userNom = $_SESSION['user_nom'] ?? 'Utilisateur';
$userPrenom = $_SESSION['user_prenom'] ?? '';
$userRole = $_SESSION['user_role'] ?? 'Participant';
$initiales = strtoupper(mb_substr($userPrenom, 0, 1) . mb_substr($userNom, 0, 1)) ?: 'U';

// Gestion de la modale d'inscription en 3 étapes via l'URL (?step=1, 2 ou 3)
$step = isset($_GET['step']) ? (int) $_GET['step'] : 0;
$selectedSlot = isset($_GET['slot']) ? (int) $_GET['slot'] : null;
$isRegistered = isset($_GET['registered']) && $_GET['registered'] === '1';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Détail de l'expérimentation - Le Mans Université</title>
    <!-- Réutilisation de la feuille de style principale -->
    <link rel="stylesheet" href="../style/Partstyle.css" />
</head>

<body>

    <div class="app-shell">

        <!-- EN-TÊTE IDENTIQUE À PartAcc.php (Réutilise 100% du CSS existant) -->
        <header class="site-header">
            <a class="brand" href="PartAcc.php" aria-label="Le Mans Université">
                <img src="../assets/img/logoLemansBlancSurBleu.png" alt="Le Mans Université" />
            </a>

            <nav class="main-nav" aria-label="Navigation principale">
                <a class="nav-link" href="#dashboard">Dashboard</a>
                <a class="nav-link active" href="PartAcc.php">Projets</a>
                <a class="nav-link" href="#ressources">Ressources</a>
                <a class="nav-link" href="#messagerie">Messagerie</a>
            </nav>

            <div class="header-actions">
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
                        <a href="#profil" class="dropdown-item" role="menuitem"><span class="fa">&#xf007;</span> Mon
                            profil</a>
                        <a href="#parametres" class="dropdown-item" role="menuitem"><span class="fa">&#xf013;</span>
                            Paramètres</a>
                        <a href="../controller.php?action=logout" class="dropdown-item danger" role="menuitem"><span
                                class="fa">&#xf2f5;</span> Se déconnecter</a>
                    </div>
                </div>
            </div>
        </header>

        <!-- BANDEAU HERO DE L'EXPÉRIMENTATION -->
        <section class="project-hero">
            <div class="hero-photo"></div>
            <div class="hero-shade"></div>
            <div class="hero-content">
                <a class="back-link" href="PartAcc.php">
                    <span class="fa">&#xf060;</span> Retour aux expérimentations
                </a>
                <div class="badges">
                    <span>Robotique &amp; Ergonomie</span>
                    <span class="status"><span class="fa">&#xf111;</span> En cours</span>
                </div>
                <h1>Robot Porteur Humanoïde — Assistance physique sans infrastructure</h1>
                <div class="project-meta">
                    <span><span class="fa">&#xf4ff;</span> Responsable : Dr. L. Oubahssi</span>
                    <span><span class="fa">&#xf133;</span> Début : 1 Septembre 2026</span>
                    <span><span class="fa">&#xf133;</span> Fin : 24 Décembre 2026</span>
                    <span><span class="fa">&#xf1ad;</span> Site : IUT Laval</span>
                </div>
            </div>
        </section>

        <!-- CORPS DE LA PAGE -->
        <main class="project-body">
            <div class="content-column">

                <!-- Encart Logistique -->
                <article class="logistics-card">
                    <h2><span class="fa">&#xf085;</span> Logistique</h2>
                    <dl>
                        <div>
                            <dt>Salle assignée :</dt>
                            <dd>Halle Robotique Laval</dd>
                        </div>
                        <div>
                            <dt>Équipement réservé :</dt>
                            <dd>Robot Porteur H1</dd>
                        </div>
                        <div>
                            <dt>Chercheur assigné :</dt>
                            <dd>
                                <span>Dr. L. Oubahssi</span>
                                <span>Dr. S. Georges</span>
                            </dd>
                        </div>
                        <div>
                            <dt>Nombre de participants :</dt>
                            <dd>12/14</dd>
                        </div>
                    </dl>
                </article>

                <!-- Encart Description -->
                <article class="description-card">
                    <header>
                        <h2><span class="fa">&#xf036;</span> Description &amp; Objectifs Scientifiques</h2>
                        <a href="#rapports" class="btn-reports">Voir les rapports d’activités</a>
                    </header>
                    <p>
                        Ce projet étudie l’intégration de robots porteurs humanoïdes dans des environnements
                        de travail industriels ou logistiques. L’objectif principal est de réduire les
                        troubles musculosquelettiques (TMS) liés aux tâches répétitives de portage lourd,
                        sans nécessiter la modification des infrastructures existantes.
                    </p>
                    <p>
                        Les données biométriques et ergonomiques collectées lors des phases
                        d’expérimentation permettront d’ajuster les algorithmes de suivi de trajectoire et
                        de commande d’effort du robot.
                    </p>
                </article>
            </div>

            <!-- Bouton d'inscription -->
            <?php if ($isRegistered): ?>
                <span class="register-button registered">Inscrit</span>
            <?php else: ?>
                <a href="?step=1" class="register-button">S’inscrire</a>
            <?php endif; ?>
        </main>

        <!-- PIED DE PAGE IDENTIQUE À PartAcc.php (Réutilise 100% du CSS existant) -->
        <footer>
            <span class="fa">&#xf19d;</span>
            <p>
                <strong>Le Mans Université</strong> • <?= htmlspecialchars($userRole) ?> - Fiche détaillée
                expérimentation
            </p>
        </footer>

        <!-- MODALE D'INSCRIPTION EN 3 ÉTAPES (Gérée en PHP) -->
        <?php if ($step >= 1 && $step <= 3): ?>
            <div class="modal-backdrop" role="dialog" aria-modal="true">
                <div class="consent-modal">
                    <a href="PartExpSess.php" class="modal-close" aria-label="Fermer">×</a>

                    <p class="modal-kicker">
                        <?= $step === 1 ? 'fiche consentement' : ($step === 2 ? 'sélection créneaux' : 'confirmation') ?>
                    </p>

                    <div class="progress-steps" aria-label="Étape <?= $step ?> sur 3">
                        <?php for ($i = 1; $i <= 3; $i++): ?>
                            <span class="<?= $i <= $step ? 'active' : '' ?>"><?= $i ?></span>
                        <?php endfor; ?>
                    </div>

                    <?php if ($step === 1): ?>
                        <h2>Fiche de consentement :</h2>
                        <p>Voici la liste des éléments sur lesquels vous vous engagez :</p>
                        <ul class="consent-list">
                            <li>Engagement de confidentialité sur le protocole</li>
                            <li>Accord pour la collecte de données biométriques anonymisées</li>
                            <li>Droit de retrait à tout moment sans justification</li>
                        </ul>
                        <div class="modal-actions">
                            <a href="PartExpSess.php" class="secondary-button">Retour</a>
                            <a href="?step=2" class="next-button">
                                <strong>Accepter</strong>
                                <small>Suivant</small>
                            </a>
                        </div>

                    <?php elseif ($step === 2): ?>
                        <h2>Sélection des créneaux horaires :</h2>
                        <form method="GET" action="PartExpSess.php" class="slot-form">
                            <input type="hidden" name="step" value="3" />
                            <div class="slot-list">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <label>
                                        <input type="radio" name="slot" value="<?= $i ?>" required />
                                        <span>Horaire <?= $i ?></span>
                                    </label>
                                <?php endfor; ?>
                            </div>
                            <div class="modal-actions">
                                <a href="?step=1" class="secondary-button">Retour</a>
                                <button type="submit" class="next-button">
                                    <strong>Valider</strong>
                                    <small>Suivant</small>
                                </button>
                            </div>
                        </form>

                    <?php elseif ($step === 3): ?>
                        <div class="confirmation">
                            <span class="fa">&#xf00c;</span>
                            <h2>Inscription confirmée</h2>
                            <p>Votre demande pour l’horaire <?= htmlspecialchars((string) $selectedSlot) ?> a bien été
                                enregistrée.</p>
                        </div>
                        <div class="modal-actions">
                            <a href="?registered=1" class="next-button done">Terminer</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>

</body>

</html>