<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Espace Enseignant-Chercheur · LIUM - Le Mans Université</title>
    <link rel="stylesheet" href="../style/ECDashboard.css" />
  </head>
  <body>
    <div class="app-shell">
      <?php
        include '../model/ECHeaderModel.php';
      ?>

      <main class="dashboard-content">
        <section class="welcome-banner">
          <div class="banner-orbit" aria-hidden="true"></div>
          <div class="welcome-copy">
            <p class="eyebrow">Espace Enseignant-Chercheur · LIUM</p>
            <h1>Bonjour Dr. L. Oubahssi</h1>
            <p>
              Pilotez vos expérimentations, suivez les projets de recherche et retrouvez les
              ressources partagées du laboratoire.
            </p>
          </div>
        </section>

        <section class="overview-section" aria-labelledby="overview-heading">
          <div class="section-heading">
            <h2 id="overview-heading">Vue d’ensemble</h2>
            <p>Mis à jour aujourd’hui à 09:42</p>
          </div>

          <div class="overview-grid">
            <article class="overview-card">
              <div class="card-top">
                <span class="card-icon">
                  <img src="../assets/img/ProjetsActifs.svg" alt="" />
                </span>
                <strong class="card-value">6</strong>
              </div>
              <h3>Projets actifs</h3>
              <p>2 échéances cette semaine</p>
              <div class="card-actions">
                <button>Proposer un projet</button>
                <button>Voir mes projets</button>
              </div>
            </article>

            <article class="overview-card">
              <div class="card-top">
                <span class="card-icon">
                  <img src="../assets/img/Expérimentations.svg" alt="" />
                </span>
                <strong class="card-value">12</strong>
              </div>
              <h3>Expérimentations</h3>
              <p>8 en cours · 4 à valider</p>
              <div class="card-actions">
                <button>Créer une expérimentation</button>
              </div>
            </article>

            <article class="overview-card">
              <div class="card-top">
                <span class="card-icon">
                  <img src="../assets/img/Participants.svg" alt="" />
                </span>
                <strong class="card-value">24</strong>
              </div>
              <h3>Participants</h3>
              <p>+5 sur les 30 derniers jours</p>
            </article>

            <article class="overview-card">
              <div class="card-top">
                <span class="card-icon">
                  <img src="../assets/img/RessourcesPartagées.svg" alt="" />
                </span>
                <strong class="card-value">18</strong>
              </div>
              <h3>Ressources partagées</h3>
              <p>3 nouveaux documents</p>
            </article>
          </div>
        </section>

        <section class="activity-layout">
          <article class="recent-card">
            <h2>Expérimentations récentes</h2>
            <div class="experiment-list">
              <div class="experiment-row">
                <span class="status-marker green" aria-hidden="true"></span>
                <div class="experiment-info">
                  <h3>Analyse des interactions pédagogiques</h3>
                  <p>En cours</p>
                  <button>
                    <span>Voir la fiche</span>
                    <span class="font-awesome" aria-hidden="true">&#xf061;</span>
                  </button>
                </div>
                <time>21 sept.</time>
              </div>

              <div class="experiment-row">
                <span class="status-marker terracotta" aria-hidden="true"></span>
                <div class="experiment-info">
                  <h3>Assistant IA pour l’apprentissage</h3>
                  <p>À valider</p>
                  <button>
                    <span>Voir la fiche</span>
                    <span class="font-awesome" aria-hidden="true">&#xf061;</span>
                  </button>
                </div>
                <time>22 sept.</time>
              </div>

              <div class="experiment-row">
                <span class="status-marker green" aria-hidden="true"></span>
                <div class="experiment-info">
                  <h3>Corpus multimodal étudiant</h3>
                  <p>En cours</p>
                  <button>
                    <span>Voir la fiche</span>
                    <span class="font-awesome" aria-hidden="true">&#xf061;</span>
                  </button>
                </div>
                <time>23 sept.</time>
              </div>
            </div>
          </article>

          <aside class="deadline-card">
            <h2>Prochaine échéance</h2>
            <div class="deadline-date">
              <strong>28</strong>
              <span>Septembre 2026</span>
            </div>
            <p>Clôture de la collecte · Projet ORION</p>
          </aside>
        </section>
      </main>

      <footer>
        <span class="font-awesome university-icon" aria-hidden="true">&#xf19c;</span>
        <p>
          <strong>Le Mans Université</strong> <span>• Dashboard</span>
        </p>
      </footer>
    </div>
  </body>
</html>