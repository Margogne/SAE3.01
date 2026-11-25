<?php
include "../controller/ECCreateExpController.php";
?>
<!doctype html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Créer une expérimentation - Le Mans Université</title>
  <link rel="stylesheet" href="../style/ECCreateExp.css" />
  <link rel="stylesheet" href="../style/ECheader.css" />


</head>

<body>
  <div class="app-shell">
    <header class="site-header">

      <button class="brand" aria-label="Retour au tableau de bord">
        <a href="ECDashboard.php"><img src="../assets/img/logoLemansBlancSurBleu.svg" alt="Le Mans Université" /></a>
      </button>

      <nav class="main-navigation" aria-label="Navigation principale">
        <a href="ECDashboard.php"><button class="nav-link">Dashboard</button></a>
        <button class="nav-link active">Projets</button>
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
    <section class="page-heading">
      <div class="page-heading-inner">
        <a href="ECDashboard.php" class="back-link">
          <span class="fa-icon" aria-hidden="true">&#xf060;</span>
          Retour aux projets
        </a>
        <h1>
          <span class="fa-icon" aria-hidden="true">&#xf65e;</span>
          Créer une expérimentation
        </h1>
      </div>
    </section>

    <section class="stepper" aria-label="Étapes de création">
      <div class="step">
        <span>1</span>
        <b>Étape 1 : Fiche &amp; Objectifs de l’expérimentation</b>
      </div>
      <div class="step">
        <span>2</span>
        <b>Étape 2 : Engagement RGPD &amp; Validation</b>
      </div>
    </section>

    <main id="main" class="main-content">
      <div class="content-grid">
        <form class="experiment-form" method="POST">
          <section class="form-card first-card">
            <h2>
              <span class="fa-icon" aria-hidden="true">&#xf044;</span>
              Étape 1 : Création globale de l’expérimentation
            </h2>

            <label class="field project-field">
              <span>Projet associé *</span>
              <select name="id_proj" required>
                <option value="" disabled selected>Choisir un projet</option>
                <?php
                $getParams = array_change_key_case($_GET, CASE_LOWER);
                $idProjSelectionne = (int) ($getParams['id_proj'] ?? 0);
                
                foreach (get_projets() as $projet):
                  $idProj = (int) $projet['id_proj'];
                  $selected = ($idProjSelectionne > 0 && $idProj === $idProjSelectionne) ? 'selected' : '';
                  ?>
                  <option value="<?= $idProj ?>" <?= $selected ?>>
                    <?= htmlspecialchars($projet['nom_proj'] ?? '') ?>
                  </option>
                <?php endforeach; ?>

                <!--<option value="" disabled selected>Choisir un projet</option>
                <?php foreach (get_projets() as $projet) { ?>
                  <option value="<?= (int) $projet['id_proj']; ?>"> <?php echo $projet['nom_proj']; ?></option>
                <?php } ?>-->
              </select>
            </label>

            <label class="field">
              <span>Titre de l’expérimenation *</span>
              <input name="titre_exp" required type="text"
                placeholder="Ex: Étude ergonomique des casques VR en environnement pédagogique" />
            </label>

            <label class="field">
              <span>Description courte (résumé pour les catalogues/listes) *</span>
              <input name="desc_exp" required type="text"
                placeholder="Résumé en 1 ou 2 phrases pour l'affichage synthétique dans l'application..." />
            </label>

            <div class="date-row">
              <label class="field">
                <span>Date de Début de l’experimentation *</span>
                <input name="datedeb_exp" required type="date" />
              </label>
              <label class="field">
                <span>Date de Fin de l’expérimentation *</span>
                <input name="datefin_exp" required type="date" />
              </label>
            </div>
          </section>

          <section class="form-card rgpd-card">
            <h2>
              <span class="fa-icon" aria-hidden="true">&#xf3ed;</span>
              Étape 2 : Acceptation &amp; Conformité RGPD
            </h2>
            <p class="rgpd-intro">
              Conformément à la réglementation sur la protection des données personnelles, tout
              projet de recherche mené au sein de
              <br />
              Le Mans Université traitant des données de volontaires doit respecter les directives
              du comité d’éthique de Le Mans Université.
            </p>
            <label class="consent-box">
              <input name="consent" required type="checkbox" />
              <span>
                <strong>Engagement de l'Enseignant-Chercheur :</strong> Je certifie que les
                données collectées dans le cadre de ce projet feront l'objet
                <br />
                d'une anonymisation stricte, conformément à la charte éthique et RGPD validée
                par le DPO de l'établissement.
              </span>
            </label>
          </section>

          <div class="form-actions">
            <button class="cancel-button" type="reset">Annuler</button>
            <button class="submit-button" type="submit">
              <span class="fa-icon" aria-hidden="true">&#xf1d8;</span>
              Créer l’expérimentation
            </button>
          </div>
        </form>

        <aside class="info-panel">
          <section class="workflow-card">
            <h2>
              <span class="fa-icon" aria-hidden="true">&#xf4d7;</span>
              Prochaine étape du workflow
            </h2>
            <p>
              Une fois ce projet créé, le <strong>gestionnaire du laboratoire</strong> recevra
              <br />
              une notification pour :
            </p>
            <ul>
              <li>Réserver les salles et le matériel requis.</li>
              <li>Configurer le calendrier des plages de tests.</li>
              <li>Valider la publication des annonces aux participants.</li>
            </ul>
          </section>

          <section class="visibility-card">
            <h2>
              <span class="fa-icon" aria-hidden="true">&#xf06e;</span>
              Visibilité des livrables
            </h2>
            <p>
              La visibilité choisie (privée ou publique) s’appliquera aux compte-rendus et
              publications associés au projet. Vous pourrez modifier ce paramètre à tout moment
              depuis la fiche projet.
            </p>
          </section>
        </aside>
      </div>
    </main>

    <footer>
      <span class="fa-icon" aria-hidden="true">&#xf19c;</span>
      <b>Le Mans Université</b>
      <span>• EC - Création Expérimentation</span>
    </footer>
  </div>
</body>

</html>