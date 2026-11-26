<!doctype html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Gestion des participants — Le Mans Université" />
  <title>Gestion des participants</title>
  <link rel="stylesheet" href="../style/ECListePart.css" />
  <link rel="stylesheet" href="../style/ECheader.css" />

</head>

<body>
  <div class="app-shell">
    <?php
    include "../controller/ECController.php";
    ?>
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
            <a href="?id_exp=<?= $_GET['id_exp'] ?>&lang=FR" class="dropdown-item selected" role="menuitem">FR <span
                class="fa">&#xf00c;</span></a>
            <a href="?id_exp=<?= $_GET['id_exp'] ?>&lang=EN" class="dropdown-item" role="menuitem">EN</a>
            <a href="?id_exp=<?= $_GET['id_exp'] ?>&lang=ES" class="dropdown-item" role="menuitem">ES</a>
          </div>
        </div>

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

    <section class="page-heading">
      <a class="back-link" href="#">
        <span class="fa"></span> Retour à l’expérimentation
      </a>
      <h1><span class="fa"></span> Gestion des participants</h1>
      <p>Modifiez les restrictions et rétributions des participants et acceptez les demandes.</p>
    </section>

    <main class="dashboard">
      <div class="lists-toolbar">
        <div></div>
        <h2><img src="../assets/img/Profil.svg" alt="" />Inscrits</h2>
        <!--<h2><img src="../assets/img/Profil.svg" alt="" />En attente</h2>
          <button class="action-button orange"><span class="fa"></span>Créer une invitation</button>-->
      </div>
      <?php if (count(get_participants_from_exp($_GET['id_exp'])) == 0) {
        echo 'Aucun participant';
      } else {
        echo count(get_participants_from_exp($_GET['id_exp'])) . ' Participant(s)' ?>
        <div class="participant-grid">
          <section class="participant-column">
            <h2><img src="/assets/ea0ca.svg" alt="" />Inscrits</h2>
            <div class="table-head registered">
              <span>Nom</span><span>Prénom</span><span>Date d’inscription</span>
            </div>
            <div class="participant-list">


              <!-- Louka, prend juste lui comme modele -->
              <?php foreach (get_participants_from_exp($_GET['id_exp']) as $part) { ?>
                <article class="participant-row registered">
                  <div class="participant-data">
                    <strong><?= $part['nom_util'] ?></strong><span><?= $part['prenom_util'] ?></span><!--<span>10 septembre 2026</span>-->
                  </div>
                  <div class="row-actions">
                    <button class="action-button orange">Voir le profil<img src="../assets/img/arrow.svg" alt="" /></button>
                    <button class="action-button green">Rétribuer<img src="../assets/img/ticket.svg" alt="" /></button>
                    <button class="action-button red">Retirer<img src="../assets/img/Cross.svg" alt="" /></button>
                  </div>
                </article>
              <?php } ?>



              <!--<article class="participant-row registered">
                <div class="participant-data"><strong>Dubois</strong><span>Thomas</span><span>10 septembre 2026</span></div>
                <div class="row-actions">
                  <button class="action-button orange">Voir le profil<img src="../assets/img/arrow.svg" alt="" /></button>
                  <button class="action-button green">Rétribuer<img src="../assets/img/check.svg" alt="" /></button>
                  <button class="action-button red">Retirer<img src="../assets/img/Cross.svg" alt="" /></button>
                </div>
              </article>
              <article class="participant-row registered">
                <div class="participant-data"><strong>Dubois</strong><span>Thomas</span><span>10 septembre 2026</span></div>
                <div class="row-actions">
                  <button class="action-button orange">Voir le profil<img src="/assets/a70f2.svg" alt="" /></button>
                  <button class="action-button green">Rétribuer<img src="/assets/63eaa.svg" alt="" /></button>
                  <button class="action-button red">Retirer<img src="/assets/a70f2.svg" alt="" /></button>
                </div>
              </article>
              <article class="participant-row registered">
                <div class="participant-data"><strong>Dubois</strong><span>Thomas</span><span>10 septembre 2026</span></div>
                <div class="row-actions">
                  <button class="action-button orange">Voir le profil<img src="/assets/a70f2.svg" alt="" /></button>
                  <button class="action-button green">Rétribuer<img src="/assets/63eaa.svg" alt="" /></button>
                  <button class="action-button red">Retirer<img src="/assets/a70f2.svg" alt="" /></button>
                </div>
              </article>
              <article class="participant-row registered">
                <div class="participant-data"><strong>Dubois</strong><span>Thomas</span><span>10 septembre 2026</span></div>
                <div class="row-actions">
                  <button class="action-button orange">Voir le profil<img src="/assets/a70f2.svg" alt="" /></button>
                  <button class="action-button green">Rétribuer<img src="/assets/63eaa.svg" alt="" /></button>
                  <button class="action-button red">Retirer<img src="/assets/a70f2.svg" alt="" /></button>
                </div>
              </article>
              <article class="participant-row registered">
                <div class="participant-data"><strong>Dubois</strong><span>Thomas</span><span>10 septembre 2026</span></div>
                <div class="row-actions">
                  <button class="action-button orange">Voir le profil<img src="/assets/a70f2.svg" alt="" /></button>
                  <button class="action-button green">Rétribuer<img src="/assets/63eaa.svg" alt="" /></button>
                  <button class="action-button red">Retirer<img src="/assets/a70f2.svg" alt="" /></button>
                </div>
              </article>
              <article class="participant-row registered">
                <div class="participant-data"><strong>Dubois</strong><span>Thomas</span><span>10 septembre 2026</span></div>
                <div class="row-actions">
                  <button class="action-button orange">Voir le profil<img src="/assets/a70f2.svg" alt="" /></button>
                  <button class="action-button green">Rétribuer<img src="/assets/63eaa.svg" alt="" /></button>
                  <button class="action-button red">Retirer<img src="/assets/a70f2.svg" alt="" /></button>
                </div>
              </article>
              <article class="participant-row registered">
                <div class="participant-data"><strong>Dubois</strong><span>Thomas</span><span>10 septembre 2026</span></div>
                <div class="row-actions">
                  <button class="action-button orange">Voir le profil<img src="/assets/a70f2.svg" alt="" /></button>
                  <button class="action-button green">Rétribuer<img src="/assets/63eaa.svg" alt="" /></button>
                  <button class="action-button red">Retirer<img src="/assets/a70f2.svg" alt="" /></button>
                </div>
              </article>-->
            </div>
          </section>

          <!--<section class="participant-column">
            <h2><img src="/assets/ea0ca.svg" alt="" />En attente</h2>
            <div class="table-head pending"><span>Nom</span><span>Prénom</span></div>
            <div class="participant-list">
              <article class="participant-row pending">
                <div class="participant-data"><strong>Delapierre</strong><span>Vincent</span></div>
                <div class="row-actions">
                  <button class="action-button orange">Voir le profil<img src="/assets/a70f2.svg" alt="" /></button>
                  <span class="decision-actions">
                    <button class="action-button green">Accepter<img src="/assets/5427b.svg" alt="" /></button>
                    <button class="action-button red">Refuser<img src="/assets/a70f2.svg" alt="" /></button>
                  </span>
                </div>
              </article>
              <article class="participant-row pending">
                <div class="participant-data"><strong>Delapierre</strong><span>Vincent</span></div>
                <div class="row-actions">
                  <button class="action-button orange">Voir le profil<img src="/assets/a70f2.svg" alt="" /></button>
                  <span class="decision-actions">
                    <button class="action-button green">Accepter<img src="/assets/5427b.svg" alt="" /></button>
                    <button class="action-button red">Refuser<img src="/assets/a70f2.svg" alt="" /></button>
                  </span>
                </div>
              </article>
              <article class="participant-row pending">
                <div class="participant-data"><strong>Delapierre</strong><span>Vincent</span></div>
                <div class="row-actions">
                  <button class="action-button orange">Voir le profil<img src="/assets/a70f2.svg" alt="" /></button>
                  <span class="decision-actions">
                    <button class="action-button green">Accepter<img src="/assets/5427b.svg" alt="" /></button>
                    <button class="action-button red">Refuser<img src="/assets/a70f2.svg" alt="" /></button>
                  </span>
                </div>
              </article>
              <article class="participant-row pending">
                <div class="participant-data"><strong>Delapierre</strong><span>Vincent</span></div>
                <div class="row-actions">
                  <button class="action-button orange">Voir le profil<img src="/assets/a70f2.svg" alt="" /></button>
                  <span class="decision-actions">
                    <button class="action-button green">Accepter<img src="/assets/5427b.svg" alt="" /></button>
                    <button class="action-button red">Refuser<img src="/assets/a70f2.svg" alt="" /></button>
                  </span>
                </div>
              </article>
              <article class="participant-row pending">
                <div class="participant-data"><strong>Delapierre</strong><span>Vincent</span></div>
                <div class="row-actions">
                  <button class="action-button orange">Voir le profil<img src="/assets/a70f2.svg" alt="" /></button>
                  <span class="decision-actions">
                    <button class="action-button green">Accepter<img src="/assets/5427b.svg" alt="" /></button>
                    <button class="action-button red">Refuser<img src="/assets/a70f2.svg" alt="" /></button>
                  </span>
                </div>
              </article>
              <article class="participant-row pending">
                <div class="participant-data"><strong>Delapierre</strong><span>Vincent</span></div>
                <div class="row-actions">
                  <button class="action-button orange">Voir le profil<img src="/assets/a70f2.svg" alt="" /></button>
                  <span class="decision-actions">
                    <button class="action-button green">Accepter<img src="/assets/5427b.svg" alt="" /></button>
                    <button class="action-button red">Refuser<img src="/assets/a70f2.svg" alt="" /></button>
                  </span>
                </div>
              </article>
              <article class="participant-row pending">
                <div class="participant-data"><strong>Delapierre</strong><span>Vincent</span></div>
                <div class="row-actions">
                  <button class="action-button orange">Voir le profil<img src="/assets/a70f2.svg" alt="" /></button>
                  <span class="decision-actions">
                    <button class="action-button green">Accepter<img src="/assets/5427b.svg" alt="" /></button>
                    <button class="action-button red">Refuser<img src="/assets/a70f2.svg" alt="" /></button>
                  </span>
                </div>
              </article>
            </div>
          </section>-->
        </div>
      <?php } ?>

      <div class="settings-grid">
        <section class="panel reward-panel">
          <div class="panel-heading dark-rule">
            <h2><img src="../assets/img/Wallet.svg" alt="" />Gestion des rétributions</h2>
            <button class="action-button navy">Modifier</button>
          </div>
          <div class="reward-table">
            <div><span>Somme</span><strong>10</strong></div>
            <div><span>Devise</span><strong>€</strong></div>
            <div><span>Type</span><strong>Code réduction</strong></div>
            <div><span>Site commerçant</span><strong>Amazon : https://www.amazon.fr/</strong></div>
          </div>
          <button class="action-button navy">Ajouter</button>
        </section>

        <!--<section class="panel restrictions-panel">
            <div class="panel-heading">
              <h2><span class="fa pdf-icon"></span>Restrictions</h2>
              <button class="action-button orange">Ajouter<img src="../assets/img/Plus.svg" alt="" /></button>
            </div>
            <div class="restriction-list">
              <div class="restriction-item">
                <strong>Age minimum : 18</strong>
                <button><img src="../assets/img/Gear.svg" alt="" />Modifier</button>
              </div>
              <div class="restriction-item">
                <strong>Age maximum : 100</strong>
                <button><img src="../assets/img/Gear.svg" alt="" />Modifier</button>
              </div>
              <div class="restriction-item">
                <strong>Taille minimum : 1m65</strong>
                <button><img src="../assets/img/Gear.svg" alt="" />Modifier</button>
              </div>
              <div class="restriction-item">
                <strong>Taille maximum : 2m00</strong>
                <button><img src="../assets/img/Gear.svg" alt="" />Modifier</button>
              </div>
            </div>
          </section>-->
      </div>
    </main>

    <footer>
      <span class="fa"></span>
      <strong>Le Mans Université</strong>
      <span>• Liste des participants</span>
    </footer>
  </div>
</body>

</html>