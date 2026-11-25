<?php
session_start();
require_once '../model/model.php';

$pageData = prepare_part_profil_page_data($_SESSION, $_SERVER['REQUEST_METHOD'] ?? 'GET', $_POST);
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
    <title>Mon Profil Utilisateur - Le Mans Université</title>
    <link rel="stylesheet" href="../style/PartGenStyle.css" />
    <link rel="stylesheet" href="../style/PartProfilStyle.css" />
</head>

<body>
    <div class="app-shell">
        <!-- HEADER -->
        <header class="site-header" id="siteHeader">
            <a class="brand" href="PartAcc.php" aria-label="Le Mans Université">
                <img src="../assets/img/logoLemansBlancSurBleu.png" alt="Le Mans Université" />
            </a>

            <nav class="main-nav" aria-label="Navigation principale">
                <a class="nav-link" href="PartAcc.php">Expérimentations</a>
                <a class="nav-link" href="PartMess.php">Messagerie</a>
            </nav>

            <!-- language menu, notifications, and profile menu -->
            <div class="header-actions">

                <!-- Language Menu -->
                <div class="menu-anchor language-anchor">
                    <button type="button" class="header-button language-button" aria-haspopup="menu">
                        <span>FR</span>
                        <span class="fa" style="font-size: 9px;">&#xf078;</span>
                    </button>
                    <div class="dropdown language-menu" role="menu" style="display: none;">
                        <a href="?lang=FR" class="dropdown-item selected" role="menuitem">FR <span
                                class="fa">&#xf00c;</span></a>
                        <a href="?lang=EN" class="dropdown-item" role="menuitem">EN</a>
                        <a href="?lang=ES" class="dropdown-item" role="menuitem">ES</a>
                    </div>
                </div>

                <!-- Notifications Menu -->
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

                <!-- Profile Menu -->
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
                        <a href="PartProfil.php" class="dropdown-item" role="menuitem"><span class="fa">&#xf007;</span>
                            Mon profil</a>
                        <a href="#parametres" class="dropdown-item" role="menuitem"><span class="fa">&#xf013;</span>
                            Paramètres</a>
                        <a href="../controller.php?action=logout" class="dropdown-item danger" role="menuitem"><span
                                class="fa">&#xf2f5;</span> Se déconnecter</a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Bandeau haut de page -->
        <section class="page-heading">
            <h1>
                <span class="fa">&#xf007;</span>
                Modifier votre Profil Utilisateur
            </h1>
        </section>

        <!-- Menu de profil -->
        <main class="profil-main-content">
            <section class="form-panel" aria-label="modification du profil utilisateur">
                <form method="POST" action="PartProfil.php" class="profile-form">
                    <!-- champ Nom -->
                    <label>
                        <span class="sr-only">Nom</span>
                        <input type="text" name="lastName" value="<?= htmlspecialchars($formData['lastName']) ?>"
                            placeholder="Nom" required />
                    </label>

                    <!-- champ Prénom -->
                    <label>
                        <span class="sr-only">Prénom</span>
                        <input type="text" name="firstName" value="<?= htmlspecialchars($formData['firstName']) ?>"
                            placeholder="Prénom" required />
                    </label>

                    <!-- champ Âge -->
                    <label>
                        <span class="sr-only">Date de naissance</span>
                        <input type="date" name="birthDate" value="<?= htmlspecialchars($formData['birthDate']) ?>" />
                    </label>

                    <!-- champ Genre -->
                    <label>
                        <span class="sr-only">Genre</span>
                        <select name="gender" required>
                            <option value="" disabled <?= $formData['gender'] === '' ? 'selected' : '' ?>>Genre</option>
                            <option value="F" <?= in_array($formData['gender'], ['F', 'femme'], true) ? 'selected' : '' ?>>Femme</option>
                            <option value="M" <?= in_array($formData['gender'], ['M', 'homme'], true) ? 'selected' : '' ?>>Homme</option>
                            <option value="non-binaire" <?= $formData['gender'] === 'non-binaire' ? 'selected' : '' ?>>
                                Non-binaire
                            </option>
                            <option value="non-precise" <?= $formData['gender'] === 'non-precise' ? 'selected' : '' ?>>Je
                                préfère ne pas préciser
                            </option>
                        </select>
                    </label>

                    <!-- champ Autres spécificités -->
                    <label>
                        <span class="sr-only">Autres spécificités</span>
                        <textarea name="details"
                            placeholder="Autres Spécificités"><?= htmlspecialchars($formData['details']) ?></textarea>
                    </label>

                    <!-- système -->
                    <button type="submit" class="submit-button">Valider</button>

                    <?php if ($errorMessage !== ''): ?>
                        <p class="error-message" role="alert"><?= htmlspecialchars($errorMessage) ?></p>
                    <?php elseif ($submitted): ?>
                        <p class="success-message visible" aria-live="polite">Votre profil a bien été enregistré.</p>
                    <?php endif; ?>
                </form>
            </section>

            <figure class="workspace-photo">
                <img src="../assets/img/teamwork.png"
                    alt="Deux chercheurs travaillant devant plusieurs écrans dans un laboratoire" />
            </figure>
        </main>

        <footer>
            <span class="fa">&#xf19d;</span>
            <p>
                <strong>Le Mans Université</strong> • <?= htmlspecialchars($userRole) ?> - Mon profil Utilisateur
            </p>
        </footer>

    </div>
</body>

</html>