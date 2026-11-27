<?php
include("../controller/ECController.php");
?>

<!doctype html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Gestion des rapports d’activités de l’expérimentation" />
  <title>EC — Rapports d’activités</title>
  <link rel="stylesheet" href="../style/ECListeExp.css" />
  <link rel="stylesheet" href="../style/ECheader.css" />

</head>

<body>
  <header class="site-header">

    <button class="brand" aria-label="Retour au tableau de bord">
      <a href="ECDashboard.php"><img src="../assets/img/logoLemansBlancSurBleu.svg" alt="Le Mans Université" /></a>
    </button>

    <nav class="main-navigation" aria-label="Navigation principale">
      <a href="ECDashboard.php"><button class="nav-link">Dashboard</button></a>
      <a href="ECListProjet.php"><button class="nav-link active">Projets</button></a>
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

  <div class="site-shell" id="top">


    <section class="page-heading" aria-labelledby="page-title">
      <div>
        <a class="back-link" href="ECFicheExp.php?id_exp=<?=$_GET['id_exp']?>">
          <span aria-hidden="true">←</span>
          Retour à l’expérimentation
        </a>
        <h1 id="page-title">
          <span class="folder-icon" aria-hidden="true"></span>
          Gestion des rapports d’activités
        </h1>

        <p>
          Visionnez les rapports d'activités et générez-en de nouveaux.
        </p>
      </div>
    </section>

    <main>
      <section class="reports" aria-label="Rapports d’activités">
        <div class="toolbar">
          <!--<label for="project">
            Projet associé
            <select id="project" name="project">
              <option value="">Choisir un projet</option>
              <option>Robot Porteur Humanoïde</option>
              <option>Assistance physique sans infrastructure</option>
            </select>
          </label>-->
          <a class="primary-button" href="#report-form">
            <span aria-hidden="true">+</span>
            Génerer un rapport
          </a>
        </div>

        <div class="rule"></div>

        <div class="table-scroll">
          <table>
            <thead>
              <tr>
                <th>Titre</th>
                <th>Titre de l’experimentation</th>
                <th>Titre du Projet</th>
                <th>Date de Création</th>
                <!--<th>Nom du Créateur</th>-->
                <th><span class="visually-hidden">Actions</span></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach (get_rapport_from_exp($_GET['id_exp']) as $rapp): ?>
                <tr>
                  <td data-label="Titre"><?= $rapp['titre_rap'] ?></td>
                  <td data-label="Expérimentation">
                    <?= htmlspecialchars(get_experimentation($_GET['id_exp'])['titre_exp']) ?>
                  </td>
                  <td data-label="Projet">
                    <?= htmlspecialchars(get_projet_from_exp($_GET['id_exp'])['nom_proj']) ?>
                  </td>
                  <td data-label="Créé le"><?= $rapp['date_rap'] ?></td>
                  <!--<td data-label="Créateur">DR. L. Oubahssi</td>-->
                  <td><a class="view-button" href="#report-detail-<?= (int) $rapp['id_rap'] ?>">Voir</a></td>
                </tr>
              <?php endforeach; ?>
              <!--<tr>
                <td data-label="Titre">Rapport-01</td>
                <td data-label="Expérimentation">
                  Robot Porteur Humanoïde — Assistance physique sans
                  infrastructure
                </td>
                <td data-label="Projet">
                  Robot Porteur Humanoïde — Assistance physique sans
                  infrastructure
                </td>
                <td data-label="Créé le">28 septembre 2026</td>
                <td data-label="Créateur">DR. L. Oubahssi</td>
                <td><a class="view-button" href="#report-detail">Voir</a></td>
              </tr>
              <tr>
                <td data-label="Titre">Rapport-01</td>
                <td data-label="Expérimentation">
                  Robot Porteur Humanoïde — Assistance physique sans
                  infrastructure
                </td>
                <td data-label="Projet">
                  Robot Porteur Humanoïde — Assistance physique sans
                  infrastructure
                </td>
                <td data-label="Créé le">28 septembre 2026</td>
                <td data-label="Créateur">DR. L. Oubahssi</td>
                <td><a class="view-button" href="#report-detail">Voir</a></td>
              </tr>
              <tr>
                <td data-label="Titre">Rapport-01</td>
                <td data-label="Expérimentation">
                  Robot Porteur Humanoïde — Assistance physique sans
                  infrastructure
                </td>
                <td data-label="Projet">
                  Robot Porteur Humanoïde — Assistance physique sans
                  infrastructure
                </td>
                <td data-label="Créé le">28 septembre 2026</td>
                <td data-label="Créateur">DR. L. Oubahssi</td>
                <td><a class="view-button" href="#report-detail">Voir</a></td>
              </tr>
              <tr>
                <td data-label="Titre">Rapport-01</td>
                <td data-label="Expérimentation">
                  Robot Porteur Humanoïde — Assistance physique sans
                  infrastructure
                </td>
                <td data-label="Projet">
                  Robot Porteur Humanoïde — Assistance physique sans
                  infrastructure
                </td>
                <td data-label="Créé le">28 septembre 2026</td>
                <td data-label="Créateur">DR. L. Oubahssi</td>
                <td><a class="view-button" href="#report-detail">Voir</a></td>
              </tr>
              <tr class="add-row">
                <td colspan="6">
                  <a href="#report-form">Ajouter</a>
                </td>
              </tr>-->
            </tbody>
          </table>
        </div>
      </section>
    </main>

    <footer>
      <span class="university-icon" aria-hidden="true"></span>
      <strong>Le Mans Université</strong>
      <span>• EC - Rapports d’activités</span>
    </footer>
  </div>

  <section class="modal" id="report-form" aria-labelledby="form-title">
    <a class="modal-backdrop" href="" aria-label="Fermer"></a>
    <div class="modal-card">
      <a class="modal-close" href="" aria-label="Fermer">×</a>
      <p class="eyebrow">Nouveau rapport</p>
      <h2 id="form-title">Ajouter un rapport d’activité</h2>
      <form action="" method="POST">
        <label>
          Titre du rapport
          <input type="text" placeholder="Rapport-01" required name="titre_rap"/>
        </label>
        <!--<label>
          Projet
          <select required>
            <option value="">Choisir un projet</option>
            <option>Robot Porteur Humanoïde</option>
          </select>
        </label>-->
        <label>
          Description
          <textarea rows="4" placeholder="Décrivez l’activité réalisée…" name="desc_rap"></textarea>
        </label>
        <div class="form-actions">
          <a href="">Annuler</a>
          <button type="submit">Générer le rapport</button>
        </div>
      </form>
    </div>
  </section>

  <?php
  $expInfo = get_experimentation($_GET['id_exp']);
  foreach (get_rapport_from_exp($_GET['id_exp']) as $rapp): ?>
    <? php// echo  "report-detail-".$rapp['id_rap'] ?>
    <section class="modal" id="report-detail-<?= (int) $rapp['id_rap'] ?>"
      aria-labelledby="detail-title-<?= (int) $rapp['id_rap'] ?>">
      <a class="modal-backdrop" href="" aria-label="Fermer"></a>
      <article class="modal-card detail-card">
        <a class="modal-close" href="" aria-label="Fermer">×</a>
        <p class="eyebrow">Rapport d’activité</p>
        <h2 id="detail-title-<?= (int) $rapp['id_rap'] ?>">
          Rapport du <?= htmlspecialchars($rapp['date_rap']) ?>
        </h2>
        <dl>
          <div>
            <dt>Expérimentation</dt>
            <dd><?= htmlspecialchars($expInfo['titre_exp'] ?? '') ?></dd>
          </div>
          <div>
            <dt>Date de création</dt>
            <dd><?= htmlspecialchars($rapp['date_rap']) ?></dd>
          </div>
          <div>
            <dt>Participants / Incidents</dt>
            <dd><?= (int) $rapp['nbparticipant_rap'] ?> participant(s) • <?= (int) $rapp['nbincid_rap'] ?> incident(s)
            </dd>
          </div>
          <div>
            <dt>Description</dt>
            <dd><?= nl2br(htmlspecialchars($rapp['desc_rap'] ?? '')) ?></dd>
          </div>
        </dl>
        <a class="primary-button detail-close" href="">Fermer</a>
      </article>
    </section>
  <?php endforeach; ?>
</body>

</html>