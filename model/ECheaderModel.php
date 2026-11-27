<?php
$dashboard = "";
$projets = "";
$ressources = "";
$messagerie = "";
$planning = "";
function trait_eq()
{
    $GLOBALS["dashboard"] = "";
    $GLOBALS["projets"] = "";
    $GLOBALS["ressources"] = "";
    $GLOBALS["messagerie"] = "";
    $GLOBALS["planning"] = "";

    switch ($_POST['trait']) {
        case '1':
            $GLOBALS["dashboard"] = "active";
            break;
        case '2':
            $GLOBALS["projets"] = "active";
            break;
        case '3':
            $GLOBALS["ressources"] = "active";
            break;
        case '4':
            $GLOBALS["messagerie"] = "active";
            break;
        case '5':
            $GLOBALS["planning"] = "active";
            break;
    }
}
trait_eq();
?>

<header class="site-header">

    <!-- logo -->
    <button class="brand" aria-label="Retour au tableau de bord">
        <img src="../assets/img/logoLemansBlancSurBleu.svg" alt="Le Mans Université" />
    </button>

    <!-- onglets -->
    <nav class="main-navigation" aria-label="Navigation principale">
        <a href="ECDashboard.php" style="color: inherit; text-decoration: none;"><button class="nav-link <?= $dashboard ?>">Dashboard</button></a>
        <a href="ECListProjet.php" style="color: inherit; text-decoration: none;"><button class="nav-link <?= $projets ?>">Projets</button></a>
        <a href="ECRessources.php" style="color: inherit; text-decoration: none;"><button class="nav-link <?= $ressources ?>">Ressources</button></a>
        <a href="ECMess.php" style="color: inherit; text-decoration: none;"><button class="nav-link <?= $messagerie ?>">Messagerie</button></a>
        <a href="Planning.php" style="color: inherit; text-decoration: none;"><button class="nav-link <?= $planning ?>">Planning</button></a>
    </nav>

    <div class="header-actions">
        <!-- langue -->
        <div class="menu-anchor">
            <button type="button" class="language-trigger" aria-haspopup="menu" type="button" id="btn-translate-toggle" onclick="toggleLanguage()">
                <img src="../assets/img/Globe.svg" alt="" />
                <span>Langue</span>
            </button>
        </div>

        <div id="google_translate_element" style="display:none;"></div>

        <!-- notifications -->
        <div class="menu-anchor">
            <button type="button" class="icon-trigger" aria-label="Notifications" aria-haspopup="dialog">
                <img src="../assets/img/bell.svg" alt="" />
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

        <!-- profil -->
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
                <a href="../controller.php?action=logout" class="dropdown-item danger" role="menuitem"><span class="fa">&#xf2f5;</span>Se déconnecter</a>
            </div>
        </div>

    </div>

    <script>
        if (!window.__googleTranslateSetupDone) {
            window.__googleTranslateSetupDone = true;
            window.currentTranslateLang = 'fr';

            window.googleTranslateElementInit = function() {
                new google.translate.TranslateElement({
                    pageLanguage: 'fr',
                    includedLanguages: 'fr,en,es,de',
                    autoDisplay: false
                }, 'google_translate_element');
            };

            window.setLanguage = function(lang) {
                window.currentTranslateLang = lang;
                const btn = document.getElementById('btn-translate-toggle');
                if (btn) {
                    btn.textContent = (lang === 'fr') ? 'EN' : 'FR';
                }

                const select = document.querySelector('#google_translate_element select.goog-te-combo');
                if (select) {
                    select.value = lang;
                    select.dispatchEvent(new Event('change'));
                    return;
                }

                const cookieValue = (lang === 'fr') ? '' : `/fr/${lang}`;
                document.cookie = `googtrans=${cookieValue}; path=/`;
                window.location.reload();
            };

            window.toggleLanguage = function() {
                const nextLang = (window.currentTranslateLang === 'fr') ? 'en' : 'fr';
                window.setLanguage(nextLang);
            };

            const gtScript = document.createElement('script');
            gtScript.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
            document.body.appendChild(gtScript);
        }
    </script>
</header>