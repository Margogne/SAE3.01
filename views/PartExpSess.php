<?php
session_start();

require_once '../model/model.php';

// Sécurité : redirection si non connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ID.php");
    exit();
}

// Récupération de l'ID expérimentation
$idExp = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($idExp <= 0) {
    header("Location: PartAcc.php");
    exit();
}

// Chargement des données de l'exp depuis la BDD
$exp = get_exp_details($idExp);
if (!$exp) {
    header("Location: PartAcc.php");
    exit();
}

// Préparation des données de la page dans model.php
$pageData = prepare_part_exp_sess_data($exp, $_GET, $_SESSION);
extract($pageData);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($exp['titre_exp']) ?> - Le Mans Université</title>
    <link rel="stylesheet" href="../style/PartGenStyle.css" />
    <link rel="stylesheet" href="../style/PartExpStyle.css" />
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
                <a class="nav-link" href="PartAcc.php">Accueil</a>
                <a class="nav-link active" href="#Experimentation">Expérimentations</a>
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
                        <a href="?id=<?= $idExp ?>&lang=FR" class="dropdown-item selected" role="menuitem">FR <span
                                class="fa">&#xf00c;</span></a>
                        <a href="?id=<?= $idExp ?>&lang=EN" class="dropdown-item" role="menuitem">EN</a>
                        <a href="?id=<?= $idExp ?>&lang=ES" class="dropdown-item" role="menuitem">ES</a>
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
                        <a href="PartProfil.php" class="dropdown-item" role="menuitem"><span class="fa">&#xf007;</span>
                            Mon profil</a>
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
                    <span class="status"> En cours</span>
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
                        <h2><span class="fa">&#xf073;</span> Sessions disponibles</h2>
                    </header>

                    <?php if (empty($activeSessions)): ?>
                        <p>Aucune session disponible pour cette expérimentation.</p>
                    <?php else: ?>
                        <ul class="sessions-list">
                            <?php foreach ($activeSessions as $session): ?>
                                <?php
                                $sessId = (int) ($session['id_sess'] ?? 0);
                                $sessDebut = !empty($session['datedeb_sess']) ? date('d/m/Y H:i', strtotime($session['datedeb_sess'])) : 'Date début non définie';
                                $sessFin = !empty($session['datefin_sess']) ? date('d/m/Y H:i', strtotime($session['datefin_sess'])) : 'Date fin non définie';
                                $sessStatut = $session['statut_sess'] ?? 'Non précisé';
                                ?>
                                <li class="session-item">
                                    <div class="session-info">
                                        <strong>Session #<?= htmlspecialchars((string) $sessId) ?> — Du
                                            <?= htmlspecialchars($sessDebut) ?> au <?= htmlspecialchars($sessFin) ?></strong>
                                        <span class="session-status-sub"><?= htmlspecialchars($sessStatut) ?></span>
                                    </div>
                                    <!-- Bouton d'inscription -->
                                    <?php /*la variable*/ $userId = (int) ($_SESSION['user_id'] ?? 0); ?>
                                    <?php if (get_participation($sessId, $userId)): ?>
                                        <span class="session-register-btn registered">Inscrit</span>
                                    <?php else: ?>
                                        <a href="?id=<?= $idExp ?>&amp;session_id=<?= $sessId ?>&amp;step=1"
                                            class="session-register-btn">
                                            S’inscrire
                                        </a>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </article>
            </div>
        </main>

        <!-- PIED DE PAGE -->
        <footer>
            <span class="fa">&#xf19d;</span>
            <p>
                <strong>Le Mans Université</strong> • <?= htmlspecialchars($userRole) ?> - Fiche détaillée
                expérimentation
            </p>
        </footer>

        <!-- INSCRIPTION EN 3 ÉTAPES /!\ GENERER PAR IA/!\-->
        <?php if ($step >= 1 && $step <= 3): ?>
            <div class="modal-backdrop" role="dialog" aria-modal="true">
                <div class="consent-modal">
                    <a href="PartExpSess.php?id=<?= $idExp ?>" class="modal-close" aria-label="Fermer">×</a>

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
                            <a href="PartExpSess.php?id=<?= $idExp ?>" class="secondary-button">Retour</a>
                            <a href="?id=<?= $idExp ?>&amp;session_id=<?= $sessionId ?>&amp;step=2" class="next-button">
                                <strong>Accepter</strong>
                                <small>Suivant</small>
                            </a>
                        </div>

                    <?php elseif ($step === 2): ?>
                        <h2>Sélection des créneaux horaires :</h2>
                        <form method="GET" action="PartExpSess.php" class="slot-form">
                            <input type="hidden" name="id" value="<?= $idExp ?>" />
                            <input type="hidden" name="session_id" value="<?= $sessionId ?>" />
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
                                <a href="?id=<?= $idExp ?>&step=1" class="secondary-button">Retour</a>
                                <button type="submit" class="next-button">
                                    <strong>Valider</strong>
                                    <small>Suivant</small>
                                </button>
                            </div>
                        </form>

                    <?php elseif ($step === 3): ?>
                        <div class="confirmation">
                            <?php if ($inscriptionOk): ?>
                                <span class="fa">&#xf00c;</span>
                                <h2>Inscription confirmée</h2>
                                <p>
                                    Votre inscription à la session #<?= htmlspecialchars((string) $sessionId) ?>
                                    (horaire <?= htmlspecialchars((string) $selectedSlot) ?>) a bien été enregistrée.
                                </p>
                            <?php else: ?>
                                <h2>Inscription non enregistrée</h2>
                                <p><?= htmlspecialchars($inscriptionErreur ?: 'Session invalide.') ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="modal-actions">
                            <a href="?id=<?= $idExp ?>&amp;registered_session=<?= $sessionId ?>"
                                class="next-button done">Terminer</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>