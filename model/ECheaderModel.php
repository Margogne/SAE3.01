

<header class="site-header">
            
            <button class="brand" aria-label="Retour au tableau de bord">
                <img src="../assets/img/logoLemansBlancSurBleu.svg" alt="Le Mans Université" />
            </button>

            <nav class="main-navigation" aria-label="Navigation principale">
                <button class="nav-link active">Dashboard</button>
                <button class="nav-link">Projets</button>
                <button class="nav-link">Ressources</button>
                <button class="nav-link">Messagerie</button>
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