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
      <div class="menu-anchor language-anchor">
        <button class="language-trigger" aria-haspopup="menu" aria-expanded="false">
          <img src="../assets/img/Globe.svg" alt="" />
          <span>FR</span>
          <img class="small-chevron" src="../assets/img/Vector.svg" alt="" />
        </button>
      </div>

      <div class="menu-anchor">
        <button class="icon-trigger" aria-label="Notifications" aria-haspopup="dialog" aria-expanded="false">
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
  <div class="site-shell" id="top">


    <section class="page-heading" aria-labelledby="page-title">
      <div>
        <a class="back-link" href="#projects">
          <span aria-hidden="true">←</span>
          Retour à l’expérimentation
        </a>
        <h1 id="page-title">
          <span class="folder-icon" aria-hidden="true"></span>
          Gestion des rapports d’activités
        </h1>

        <p>
          Modifiez les restrictions et rétributions des participants et
          acceptez les demandes.
        </p>
      </div>
    </section>

    <main>
      <section class="reports" aria-label="Rapports d’activités">
        <div class="toolbar">
          <label for="project">
            Projet associé
            <select id="project" name="project">
              <option value="">Choisir un projet</option>
              <option>Robot Porteur Humanoïde</option>
              <option>Assistance physique sans infrastructure</option>
            </select>
          </label>
          <a class="primary-button" href="#report-form">
            <span aria-hidden="true">+</span>
            Ajouter un rapport
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
                <th>Nom du Créateur</th>
                <th><span class="visually-hidden">Actions</span></th>
              </tr>
            </thead>
            <tbody>
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
              </tr>
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
    <a class="modal-backdrop" href="#top" aria-label="Fermer"></a>
    <div class="modal-card">
      <a class="modal-close" href="#top" aria-label="Fermer">×</a>
      <p class="eyebrow">Nouveau rapport</p>
      <h2 id="form-title">Ajouter un rapport d’activité</h2>
      <form action="#top">
        <label>
          Titre du rapport
          <input type="text" placeholder="Rapport-01" required />
        </label>
        <label>
          Projet
          <select required>
            <option value="">Choisir un projet</option>
            <option>Robot Porteur Humanoïde</option>
          </select>
        </label>
        <label>
          Résumé
          <textarea rows="4" placeholder="Décrivez l’activité réalisée…"></textarea>
        </label>
        <div class="form-actions">
          <a href="#top">Annuler</a>
          <button type="submit">Ajouter le rapport</button>
        </div>
      </form>
    </div>
  </section>

  <section class="modal" id="report-detail" aria-labelledby="detail-title">
    <a class="modal-backdrop" href="#top" aria-label="Fermer"></a>
    <article class="modal-card detail-card">
      <a class="modal-close" href="#top" aria-label="Fermer">×</a>
      <p class="eyebrow">Rapport d’activité</p>
      <h2 id="detail-title">Rapport-01</h2>
      <dl>
        <div>
          <dt>Expérimentation</dt>
          <dd>Robot Porteur Humanoïde</dd>
        </div>
        <div>
          <dt>Date de création</dt>
          <dd>28 septembre 2026</dd>
        </div>
        <div>
          <dt>Créateur</dt>
          <dd>DR. L. Oubahssi</dd>
        </div>
      </dl>
      <a class="primary-button detail-close" href="#top">Fermer</a>
    </article>
  </section>
</body>

</html>