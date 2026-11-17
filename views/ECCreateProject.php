<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Proposition d'un Nouveau Projet de Recherche - Le Mans Université</title>
    <link rel="stylesheet" href="../style/ECstyle.css" />
  </head>
  <body>
    <div class="app-shell">
      <header class="site-header">
        <a class="brand" href="#main" aria-label="Le Mans Université">
          <img src="../assets/img/logoLemansBlancSurBleu.svg" alt="Le Mans Université" />
        </a>

        <nav class="main-nav" aria-label="Navigation principale">
          <a href="#dashboard">Dashboard</a>
          <a href="#projets">Projets</a>
          <a href="#ressources">Ressources</a>
          <a href="#messagerie">Messagerie</a>
        </nav>

        <div class="header-actions">
          <div class="menu-wrap">
            <button
              class="language-trigger"
              type="button"
              aria-haspopup="menu"
              aria-expanded="false"
            >
              <img src="../assets/img/Globe.svg" alt="" />
              <span>FR</span>
              <img src="../assets/img/Vector.svg" alt="" />
            </button>
          </div>

          <div class="menu-wrap">
            <button
              class="icon-trigger"
              type="button"
              aria-label="Notifications"
              aria-haspopup="dialog"
              aria-expanded="false"
            >
              <img src="../assets/img/bell.svg" alt="" />
            </button>
          </div>

          <div class="menu-wrap profile-wrap">
            <button
              class="profile-trigger"
              type="button"
              aria-haspopup="menu"
              aria-expanded="false"
            >
              <span class="avatar">LO</span>
              <span class="profile-copy">
                <strong>Dr. L. Oubahssi</strong>
                <small>Enseignant-Chercheur</small>
              </span>
              <img class="rotate" src="../assets/img/Vector.svg" alt="" />
            </button>
          </div>
        </div>
      </header>

      <section class="title-band">
        <a href="#projets">
          <span aria-hidden="true" class="fa-icon"></span>
          Retour aux projets
        </a>
        <h1>
          <span aria-hidden="true" class="fa-icon"></span>
          Proposition d'un Nouveau Projet de Recherche
        </h1>
      </section>

      <section class="stepper" aria-label="Étapes du formulaire">
        <div>
          <span>1</span>
          <strong>Étape 1 : Fiche &amp; Objectifs du Projet</strong>
        </div>
        <div>
          <span>2</span>
          <strong>Étape 2 : Engagement RGPD &amp; Validation</strong>
        </div>
      </section>

      <main id="main">
        <form>
          <section class="form-card">
            <h2>
              <span aria-hidden="true" class="fa-icon"></span>
              Étape 1 : Création globale du projet
            </h2>

            <label class="field">
              <span>Titre du projet *</span>
              <input
                id="project-title"
                name="title"
                required
                placeholder="Ex: Étude ergonomique des casques VR en environnement pédagogique"
              />
            </label>

            <label class="field">
              <span>Description courte (résumé pour les catalogues/listes) *</span>
              <input
                name="summary"
                required
                placeholder="Résumé en 1 ou 2 phrases pour l’affichage synthétique dans l’application..."
              />
            </label>

            <label class="field">
              <span>Description longue &amp; Objectifs scientifiques *</span>
              <textarea
                name="description"
                required
                placeholder="Détaillez le contexte scientifique, la méthodologie générale et les objectifs visés pour ce projet..."
              ></textarea>
            </label>

            <div class="field-grid">
              <label class="field">
                <span>Domaine de recherche *</span>
                <select name="domain" required>
                  <option value="eiah" selected>
                    EIAH (Environnements Informatiques pour l'Apprentissage Humain)
                  </option>
                  <option value="ia">Intelligence artificielle</option>
                  <option value="interaction">Interaction humain-machine</option>
                </select>
              </label>

              <label class="field">
                <span>Visibilité des livrables / documents *</span>
                <select name="visibility" required>
                  <option value="restricted" selected>Restreinte aux membres du projet</option>
                  <option value="public">Publique</option>
                  <option value="direction">Direction et gestionnaire uniquement</option>
                </select>
              </label>

              <label class="field">
                <span>Estimation de fin de projet (Pour la Direction) *</span>
                <span class="date-control">
                  <input
                    name="endDate"
                    required
                    type="date"
                    aria-label="Estimation de fin de projet"
                  />
                  
                </span>
              </label>

              <label class="field">
                <span>Modèle d’expérimentation associé (Optionnel)</span>
                <select name="model">
                  <option value="" selected>Aucun modèle (Projet vierge)</option>
                  <option value="usability">Test d’utilisabilité</option>
                  <option value="observation">Protocole d’observation</option>
                </select>
              </label>
            </div>
          </section>

          <section class="form-card rgpd-card">
            <h2>
              <span aria-hidden="true" class="fa-icon"></span>
              Étape 2 : Acceptation &amp; Conformité RGPD
            </h2>
            <p>
              Conformément à la réglementation sur la protection des données personnelles, tout
              projet de recherche mené au sein du LIUM traitant des données de volontaires doit
              respecter les directives du comité d’éthique de Le Mans Université.
            </p>
            <label class="consent">
              <input name="consent" required type="checkbox" />
              <span>
                <strong>Engagement de l’Enseignant-Chercheur :</strong> Je certifie que les données
                collectées dans le cadre de ce projet feront l’objet d’une anonymisation stricte,
                conformément à la charte éthique et RGPD validée par le DPO de l’établissement.
              </span>
            </label>
          </section>

          <div class="form-actions">
            <button type="reset" class="cancel-button">Annuler</button>
            <button type="submit" class="submit-button">
              <span aria-hidden="true" class="fa-icon"></span>
              Soumettre le projet au gestionnaire
            </button>
          </div>
        </form>

        <aside class="info-panel" aria-label="Informations sur le workflow">
          <section class="workflow-card">
            <h3>
              <span aria-hidden="true" class="fa-icon"></span>
              Prochaine étape du workflow
            </h3>
            <p>
              Une fois ce projet créé, le <strong>gestionnaire du laboratoire</strong> recevra une
              notification pour :
            </p>
            <ul>
              <li>Réserver les salles et le matériel requis.</li>
              <li>Configurer le calendrier des plages de tests.</li>
              <li>Valider la publication des annonces aux participants.</li>
            </ul>
          </section>

          <section class="visibility-card">
            <h3>
              <span aria-hidden="true" class="fa-icon"></span>
              Visibilité des livrables
            </h3>
            <p>
              La visibilité choisie (privée ou publique) s’appliquera aux compte-rendus et publications
              associés au projet. Vous pourrez modifier ce paramètre à tout moment depuis la fiche
              projet.
            </p>
          </section>
        </aside>
      </main>

      <footer>
        <span aria-hidden="true" class="fa-icon"></span>
        <strong>Le Mans Université</strong>
        <span>• Création de projet</span>
      </footer>
    </div>
  </body>
</html>
