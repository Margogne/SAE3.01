<?php
session_start();

require_once '../model/model.php';

// Sécurité : redirection si non connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ID.php");
    exit();
}

// Récupération de l'ID expérimentation
$id_exp = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id_exp <= 0) {
    header("Location: PartAcc.php");
    exit();
}

// Chargement des données de l'exp depuis la BDD
$exp = get_exp_details($id_exp);
if (!$exp) {
    header("Location: PartAcc.php");
    exit();
}

// Infos utilisateur en session
$userNom = $_SESSION['user_nom'] ?? 'Utilisateur';
$userPrenom = $_SESSION['user_prenom'] ?? '';
$userRole = $_SESSION['user_role'] ?? 'Participant';
$initiales = strtoupper(mb_substr($userPrenom, 0, 1) . mb_substr($userNom, 0, 1)) ?: 'U';

// Gestion de la modale d'inscription en 3 étapes via l'URL
$step = isset($_GET['step']) ? (int) $_GET['step'] : 0;
$selectedSlot = isset($_GET['slot']) ? (int) $_GET['slot'] : null;
$isRegistered = isset($_GET['registered']) && $_GET['registered'] === '1';

// Préparation des variables d'affichage de l'expérimentation
$responsable = !empty($exp['chercheurs'])
    ? $exp['chercheurs'][0]['prenom_util'] . ' ' . $exp['chercheurs'][0]['nom_util']
    : 'Non renseigné';

$site = !empty($exp['nom_lab'])
    ? $exp['nom_lab'] . (!empty($exp['ville_lab']) ? ' (' . $exp['ville_lab'] . ')' : '')
    : 'Non renseigné';

$dateDebut = !empty($exp['datedeb_exp']) ? date('d/m/Y', strtotime($exp['datedeb_exp'])) : 'Non définie';
$dateFin = !empty($exp['datefin_exp']) ? date('d/m/Y', strtotime($exp['datefin_exp'])) : 'Non définie';
$equipements = !empty($exp['ressources']) ? implode(', ', $exp['ressources']) : 'Aucun équipement spécifique';

// Sessions dont le statut est autre que "Terminé"
$sessionsActives = array_filter($exp['sessions'] ?? [], function ($session) {
    $statut = trim($session['statut_sess'] ?? '');
    return $statut !== '' && mb_strtolower($statut, 'UTF-8') !== 'terminé';
});
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($exp['titre_exp']) ?> - Le Mans Université</title>
    <link rel="stylesheet" href="../style/Partstyle.css" />
</head>

<body>

    <div class="app-shell">

        <!-- EN-TÊTE IDENTIQUE  -->
        <header class="site-header">
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

                <!-- langue -->
                <div class="menu-anchor language-anchor">
                    <button type="button" class="header-button language-button" aria-haspopup="menu">
                        <span>FR</span>
                        <span class="fa" style="font-size: 9px;">&#xf078;</span>
                    </button>
                    <div class="dropdown language-menu" role="menu" style="display: none;">
                        <a href="?id=<?= $id_exp ?>&lang=FR" class="dropdown-item selected" role="menuitem">FR <span
                                class="fa">&#xf00c;</span></a>
                        <a href="?id=<?= $id_exp ?>&lang=EN" class="dropdown-item" role="menuitem">EN</a>
                        <a href="?id=<?= $id_exp ?>&lang=ES" class="dropdown-item" role="menuitem">ES</a>
                    </div>
                </div>

                <!-- notifications -->
                <div class="menu-anchor">
                    <button type="button" class="header-button icon-button" aria-label="Notifications"
                        aria-haspopup="dialog">
                        <span class="fa">&#xf0f3;</span>
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

                <!-- Profil -->
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
                                class="fa">&#xf2f5;</span>Se déconnecter</a>
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
                    <span><?= htmlspecialchars($exp['nom_proj'] ?? 'Expérimentation') ?></span>
                    <span class="status"><span class="fa">&#xf111;</span> En cours</span>
                </div>
                <h1><?= htmlspecialchars($exp['titre_exp']) ?></h1>
                <div class="project-meta">
                    <span><span class="fa">&#xf4ff;</span> Responsable : <?= htmlspecialchars($responsable) ?></span>
                    <span><span class="fa">&#xf133;</span> Début : <?= htmlspecialchars($dateDebut) ?></span>
                    <span><span class="fa">&#xf133;</span> Fin : <?= htmlspecialchars($dateFin) ?></span>
                    <span><span class="fa">&#xf1ad;</span> Site : <?= htmlspecialchars($site) ?></span>
                </div>
            </div>
        </section>

        <!-- CORPS DE LA PAGE -->
        <main class="project-body">
            <div class="content-column">

                <!-- rectangle Orange -->
                <article class="logistics-card">
                    <h2><span class="fa">&#xf085;</span> Logistique &amp; Informations pratiques</h2>
                    <dl>
                        <div>
                            <dt>Durée estimée :</dt>
                            <dd><?= !empty($exp['dur_exp']) ? htmlspecialchars($exp['dur_exp']) : 'Non précisée' ?></dd>
                        </div>
                        <div>
                            <dt>Équipement réservé :</dt>
                            <dd><?= htmlspecialchars($equipements) ?></dd>
                        </div>
                        <div>
                            <dt>Chercheur(s) assigné(s) :</dt>
                            <dd>
                                <?php if (!empty($exp['chercheurs'])): ?>
                                    <?php foreach ($exp['chercheurs'] as $ch): ?>
                                        <span><?= htmlspecialchars($ch['prenom_util'] . ' ' . $ch['nom_util']) ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span>Non renseigné</span>
                                <?php endif; ?>
                            </dd>
                        </div>
                        <div>
                            <dt>Rétribution / Indemnisation :</dt>
                            <dd><?= !empty($exp['retrib_exp']) ? htmlspecialchars($exp['retrib_exp']) : 'Aucune' ?></dd>
                        </div>
                        <div>
                            <dt>Description &amp; Objectifs Scientifiques :</dt>
                            <dd><?= nl2br(htmlspecialchars($exp['desc_exp'] ?? 'Aucune description disponible pour cette expérimentation.')) ?>
                            </dd>
                        </div>
                    </dl>
                </article>

                <!-- Listing -->
                <article class="description-card">
                    <header>
                        <h2><!--<span class="fa">&#xf073;</span> --> Sessions disponibles</h2>
                    </header>

                    <?php if (empty($sessionsActives)): ?>
                        <p>Aucune session disponible pour cette expérimentation.</p>
                    <?php else: ?>
                        <ul class="sessions-list">
                            <?php foreach ($sessionsActives as $session): ?>
                                <?php
                                $sessDebut = !empty($session['datedeb_sess']) ? date('d/m/Y H:i', strtotime($session['datedeb_sess'])) : 'Date début non définie';
                                $sessFin = !empty($session['datefin_sess']) ? date('d/m/Y H:i', strtotime($session['datefin_sess'])) : 'Date fin non définie';
                                $sessStatut = $session['statut_sess'] ?? 'Non précisé';
                                ?>
                                <li class="session-item">
                                    <div class="session-info">
                                        <strong>Session #<?= htmlspecialchars((string) ($session['id_sess'] ?? '')) ?></strong>
                                        <span>Du <?= htmlspecialchars($sessDebut) ?> au <?= htmlspecialchars($sessFin) ?></span>
                                    </div>
                                    <span class="session-status"><?= htmlspecialchars($sessStatut) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </article>
            </div>

            <!-- Bouton d'inscription -->
            <?php if ($isRegistered): ?>
                <span class="register-button registered">Inscrit</span>
            <?php else: ?>
                <a href="?step=1" class="register-button">S’inscrire</a>
            <?php endif; ?>
        </main>

        <!-- PIED DE PAGE -->
        <footer>
            <span class="fa">&#xf19d;</span>
            <p>
                <strong>Le Mans Université</strong> • <?= htmlspecialchars($userRole) ?> - Fiche détaillée
                expérimentation
            </p>
        </footer>

        <!-- INSCRIPTION EN 3 ÉTAPES /!\GENERER PAR IA/!\-->
        <?php if ($step >= 1 && $step <= 3): ?>
            <div class="modal-backdrop" role="dialog" aria-modal="true">
                <div class="consent-modal">
                    <a href="PartExpSess.php?id=<?= $id_exp ?>" class="modal-close" aria-label="Fermer">×</a>

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
                            <a href="PartExpSess.php?id=<?= $id_exp ?>" class="secondary-button">Retour</a>
                            <a href="?id=<?= $id_exp ?>&step=2" class="next-button">
                                <strong>Accepter</strong>
                                <small>Suivant</small>
                            </a>
                        </div>

                    <?php elseif ($step === 2): ?>
                        <h2>Sélection des créneaux horaires :</h2>
                        <form method="GET" action="PartExpSess.php" class="slot-form">
                            <input type="hidden" name="id" value="<?= $id_exp ?>" />
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
                                <a href="?id=<?= $id_exp ?>&step=1" class="secondary-button">Retour</a>
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
                            <a href="?id=<?= $id_exp ?>&registered=1" class="next-button done">Terminer</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>

</body>

</html>