<?php
session_start();
require_once '../model/model.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ID.php');
    exit();
}

$userNom = (string) ($_SESSION['user_nom'] ?? 'Utilisateur');
$userPrenom = (string) ($_SESSION['user_prenom'] ?? '');
$userRole = (string) ($_SESSION['user_role'] ?? 'Participant');

$initChar = function (string $value): string {
    if ($value === '') {
        return '';
    }
    return function_exists('mb_substr') ? mb_substr($value, 0, 1, 'UTF-8') : substr($value, 0, 1);
};

$initials = strtoupper($initChar($userPrenom) . $initChar($userNom)) ?: 'U';

// Données d'exemple calquées sur ECHANGER (id_mess, id_util_expéditeur, id_util_receveur, date_envoi, contenu_mess)
$messages = [
    [
        'id_mess' => 1,
        'id_util_receveur' => (int) $_SESSION['user_id'],
        'id_util_expéditeur' => 10,
        'period' => 'Aujourd’hui',
        'sender' => 'Expéditeur #10',
        'contenu_mess' => 'La session du 28 septembre est déplacée à 14 h 30.',
        'date_envoi' => 'Aujourd’hui, 08:42',
        'unread' => true,
    ],
    [
        'id_mess' => 2,
        'id_util_receveur' => (int) $_SESSION['user_id'],
        'id_util_expéditeur' => 12,
        'period' => 'Aujourd’hui',
        'sender' => 'Expéditeur #12',
        'contenu_mess' => 'Le protocole de l’expérimentation a été mis à jour.',
        'date_envoi' => 'Aujourd’hui, 07:15',
        'unread' => true,
    ],
    [
        'id_mess' => 3,
        'id_util_receveur' => (int) $_SESSION['user_id'],
        'id_util_expéditeur' => 15,
        'period' => 'Hier',
        'sender' => 'Expéditeur #15',
        'contenu_mess' => 'Un membre de votre équipe a commenté vos résultats.',
        'date_envoi' => 'Hier, 16:12',
        'unread' => false,
    ],
    [
        'id_mess' => 4,
        'id_util_receveur' => (int) $_SESSION['user_id'],
        'id_util_expéditeur' => 10,
        'period' => 'Plus tôt',
        'sender' => 'Expéditeur #10',
        'contenu_mess' => 'Une nouvelle synthèse est disponible dans votre espace.',
        'date_envoi' => '20 septembre, 11:20',
        'unread' => false,
    ],
];

$status = $_GET['status'] ?? 'all';
if (!in_array($status, ['all', 'unread', 'read'], true)) {
    $status = 'all';
}

$query = trim((string) ($_GET['q'] ?? ''));

$unreadCount = count(array_filter($messages, fn($m) => $m['unread']));
$readCount = count($messages) - $unreadCount;

$filtered = array_values(array_filter($messages, function ($item) use ($status, $query) {
    $matchesStatus = $status === 'all'
        || ($status === 'unread' && $item['unread'])
        || ($status === 'read' && !$item['unread']);

    $haystack = $item['sender'] . ' ' . $item['contenu_mess'] . ' ' . $item['date_envoi'];
    $matchesQuery = ($query === '')
        || (stripos($haystack, $query) !== false);

    return $matchesStatus && $matchesQuery;
}));

$periods = ['Aujourd’hui', 'Hier', 'Plus tôt'];

/* ==============================================
   SECTION : HTML DE LA PAGE
   ============================================== */
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Messages - Le Mans Université</title>
    <link rel="stylesheet" href="../style/PartGenStyle.css" />
    <link rel="stylesheet" href="../style/PartMessStyle.css" />
</head>

<body>
    <div class="app-shell">

        <!-- HEADER -->
        <header class="site-header" id="siteHeader">

            <!-- Logo -->
            <a class="brand" href="PartAcc.php" aria-label="Le Mans Université">
                <img src="../assets/img/logoLemansBlancSurBleu.png" alt="Le Mans Université" />
            </a>

            <!-- Menu de navigation -->
            <nav class="main-nav" aria-label="Navigation principale">
                <a class="nav-link" href="PartAcc.php">Accueil</a>
                <a class="nav-link" href="#Experimentations">Expérimentations</a>
                <a class="nav-link" href="#ressources">Ressources</a>
                <a class="nav-link active" href="PartNotif.php">Notifications</a>
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
        </header>

        <main class="mess-main">

            <!-- TITRE -->
            <section class="mess-heading">
                <div>
                    <p class="mess-eyebrow">Espace <?= htmlspecialchars($userRole) ?></p>
                    <h1>Boîte de réception</h1>
                </div>
            </section>

            <!-- Résumé -->
            <section class="summary-grid" aria-label="Résumé des messages">
                <div class="summary-card">
                    <strong><?= count($messages) ?></strong>
                    <span>Messages au total</span>
                </div>
                <div class="summary-card">
                    <strong><?= $unreadCount ?></strong>
                    <span>Non lus</span>
                </div>
                <div class="summary-card">
                    <strong><?= $readCount ?></strong>
                    <span>Lus</span>
                </div>
            </section>

            <!-- barre de filtres -->
            <section class="mess-filters" aria-label="Filtres">

                <!-- filtre par état -->
                <div class="status-filter">
                    <a href="?status=all&amp;q=<?= urlencode($query) ?>"
                        class="<?= $status === 'all' ? 'active' : '' ?>">
                        Tous · <?= count($messages) ?>
                    </a>
                    <a href="?status=unread&amp;q=<?= urlencode($query) ?>"
                        class="<?= $status === 'unread' ? 'active' : '' ?>">
                        Non lus · <?= $unreadCount ?>
                    </a>
                    <a href="?status=read&amp;q=<?= urlencode($query) ?>"
                        class="<?= $status === 'read' ? 'active' : '' ?>">
                        Lus · <?= $readCount ?>
                    </a>
                </div>

                <!-- barre de recherche -->
                <form method="GET" action="PartNotif.php" class="mess-filter-form">
                    <input type="hidden" name="status" value="<?= htmlspecialchars($status) ?>" />
                    <label class="mess-search-box">
                        <span class="fa">&#xf002;</span>
                        <input type="search" name="q" value="<?= htmlspecialchars($query) ?>"
                            placeholder="Rechercher un message…" />
                    </label>

                    <button type="submit" class="btn-filter-submit">Filtrer</button>
                </form>

            </section>

            <!-- corps de page -->
            <section class="messages-section">

                <div class="list-intro">
                    <strong>
                        <?= count($filtered) ?> message(s)
                    </strong>
                </div>

                <!-- listing des messages -->
                <?php foreach ($periods as $period): ?>
                    <?php $items = array_filter($filtered, fn($m) => $m['period'] === $period); ?>
                    <?php if (empty($items))
                        continue; ?>
                    <section class="period">

                        <div class="message-list">
                            <?php foreach ($items as $item): ?>
                                <article class="message-row <?php if (!empty($item['unread'])) {
                                    echo 'is-unread'; } ?>">

                                    <!-- icône -->
                                    <div class="type-icon">
                                        <span class="fa">&#xf0e0;</span>
                                    </div>

                                    <!-- corps de message -->
                                    <div class="message-copy">
                                        <div class="message-meta">
                                            <span>Message #<?= (int) $item['id_mess'] ?></span>
                                            <a href="?delete_mess=<?= (int) $item['id_mess'] ?>&amp;status=<?= htmlspecialchars($status) ?>&amp;q=<?= urlencode($query) ?>"
                                                class="mess-delete-cross" aria-label="Supprimer le message"
                                                title="Supprimer">
                                                <span class="fa" aria-hidden="true">&#xf00d;</span>
                                            </a>
                                        </div>
                                        <strong class="message-title"><?= htmlspecialchars($item['sender']) ?></strong>
                                        <p><?= htmlspecialchars($item['contenu_mess']) ?></p>
                                        <div class="message-context">
                                            <span><?= htmlspecialchars($item['date_envoi']) ?></span>
                                        </div>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endforeach; ?>

                <?php if (empty($filtered)): ?>
                    <div class="no-results">
                        <strong>Aucun message</strong>
                        <p>Aucun résultat ne correspond à vos filtres.</p>
                    </div>
                <?php endif; ?>
            </section>
        </main>

        <footer>
            <span class="fa">&#xf19d;</span>
            <p>
                <strong>Le Mans Université</strong> • <?= htmlspecialchars($userRole) ?> - Messages
            </p>
        </footer>

    </div>
</body>

</html>