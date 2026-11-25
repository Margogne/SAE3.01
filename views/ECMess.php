<?php
session_start();
require_once '../model/model.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ID.php');
    exit();
}

$flashMessage = gestion_message_delete($_SESSION, $_GET, 'PartMess.php');

$flashDelete = gestion_message_delete($_SESSION, $_GET, 'PartMess.php');
$flashRead = gestion_message_mark_read($_SESSION, $_GET, 'PartMess.php');
$flashMessage = $flashDelete ?? $flashRead;

$pageData = prepare_part_mess_page_data($_SESSION, $_GET);
if ($pageData === null) {
    header('Location: ID.php');
    exit();
}

extract($pageData);

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
    <link rel="stylesheet" href="../style/ECheader.css" />
</head>

<body>
    <div class="app-shell">

        <!-- HEADER -->
        <header class="site-header">
            
            <button class="brand" aria-label="Retour au tableau de bord">
                <img src="../assets/img/logoLemansBlancSurBleu.svg" alt="Le Mans Université" />
            </button>

            <nav class="main-navigation" aria-label="Navigation principale">
                <a href="ECDashboard.php"><button class="nav-link">Dashboard</button></a>
                <a href="ECListProjet.php"><button class="nav-link">Projets</button></a>
                <a href="ECRessources.php"><button class="nav-link">Ressources</button></a>
                <a href="ECMess.php"><button class="nav-link active">Messagerie</button></a>
            </nav>

            <div class="header-actions">
                <div class="menu-anchor language-anchor">
                    <button class="language-trigger" aria-haspopup="menu" aria-expanded="false">
                        <img src="../assets/img/Globe.svg" alt="" />
                        <span>FR</span>
                        <img class="small-chevron" src="../assets/img/Vector.svg" alt="" />
                    </button>
                </div>

                <div class="menu-anchor">
                    <button class="icon-trigger" aria-label="Notifications" aria-haspopup="dialog"
                        aria-expanded="false">
                        <img src="../assets/img/bell.svg" alt="" />
                    </button>
                </div>

                <div class="menu-anchor profile-anchor">
                    <button class="profile-trigger" aria-haspopup="menu" aria-expanded="false">
                        <span class="avatar"><?= htmlspecialchars($initials) ?></span>
                        <span class="profile-copy">
                            <strong><?= htmlspecialchars($userNom . ' ' . $userPrenom) ?></strong>
                            <small><?= htmlspecialchars($userRole) ?></small>
                        </span>
                        <img class="profile-chevron" src="../assets/img/Vector.svg" alt="" />
                    </button>
                </div>
            </div>
        </header>

        <!-- corp de page -->
        <main class="mess-main">

            <!-- TITRE -->
            <section class="mess-heading">
                <div>
                    <p class="mess-eyebrow">Espace <?= htmlspecialchars($userRole) ?></p>
                    <h1>Boîte de réception</h1>
                </div>
            </section>

            <?php if (!empty($dbError)): ?>
                <p class="error-message" role="alert"><?= htmlspecialchars($dbError) ?></p>
            <?php endif; ?>

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

            <!-- barre de recherche -->
            <section class="mess-filters" aria-label="Filtres">

                <!-- filtre par état -->
                <div class="status-filter">
                    <a href="?status=all&amp;q=<?= urlencode($query) ?>"
                        class="<?= $status === 'all' ? 'active' : '' ?>">
                        Toutes · <?= count($messages) ?>
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
                <form method="GET" action="PartMess.php" class="mess-filter-form">
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
                    <strong><?= count($filtered) ?> message(s)</strong>
                </div>

                <!-- listing des messages -->
                <?php foreach ($periods as $period): ?>
                    <?php $items = array_filter($filtered, fn($m) => $m['period'] === $period); ?>
                    <?php if (empty($items))
                        continue; ?>
                    <section class="period">

                        <div class="message-list">
                            <?php foreach ($items as $item): ?>
                                <article class="message-row <?= is_message_unread($item) ? 'is-unread' : '' ?>">

                                    <!-- icône message -->
                                    <div class="type-icon">
                                        <span class="fa">&#xf0e0;</span>
                                    </div>

                                    <!-- corps de message -->
                                    <div class="message-copy">
                                        <div class="message-meta">
                                            <span>Message #<?= (int) $item['id_mess'] ?></span>
                                            <a href="?delete_mess=<?= (int) $item['id_mess'] ?>&amp;
                                                status=<?= htmlspecialchars($status) ?>&amp;q=<?= urlencode($query) ?>"
                                                class="mess-delete-cross" aria-label="Supprimer le message" title="Supprimer">
                                                <span class="fa" aria-hidden="true">&#xf00d;</span>
                                            </a>
                                        </div>
                                        <strong class="message-title"><?= htmlspecialchars($item['sender']) ?></strong>
                                        <p><?= htmlspecialchars($item['contenu_mess']) ?></p>

                                        <div class="message-context">
                                            <span><?= htmlspecialchars($item['date_envoi']) ?></span>
                                            
                                            <?php if (is_message_unread($item)): ?>
                                                <a href="?mark_read=<?= (int) $item['id_mess'] ?>&amp;status=<?= htmlspecialchars($status) ?>&amp;q=<?= urlencode($query) ?>"
                                                    class="btn-mark-read"
                                                    aria-label="Marquer le message #<?= (int) $item['id_mess'] ?> comme lu"
                                                    title="Marquer comme lu">
                                                    <span class="fa" aria-hidden="true">&#xf00c;</span>
                                                    <span>Marquer comme lu</span>
                                                </a>
                                            <?php endif; ?>
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