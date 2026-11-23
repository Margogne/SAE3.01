<?php 



include("../controller/ECFicheProjController.php") ;


?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fiche détaillée projet - Robot Porteur Humanoïde - Le Mans Université</title>
    <link rel="stylesheet" href="../style/ECFicheProj.css" />
  </head>
  <body>
    <div class="app-shell">
      <div class="page">
        <?php
        include '../model/ECHeaderModel.php';
        ?>

        <section class="project-hero">
          <div class="hero-image"></div>
          <div class="hero-overlay"></div>
          <div class="hero-content">
            <button class="back-link">
              <span class="fa-solid" aria-hidden="true">&#xf060;</span> Retour à la liste des projets
            </button>
            <div class="badges">
              <span class="topic">Robotique &amp; Ergonomie</span>
              <span class="status"><span class="fa-solid" aria-hidden="true">&#xf111;</span> En cours de gestion</span>
            </div>
            <h1><?php echo $projet['nom_proj'] ?>
            </h1>
            <div class="project-meta">
              <span><span class="fa-solid" aria-hidden="true">&#xf4ff;</span> Responsable : Dr. L. Oubahssi</span>
              <span><span class="fa-solid" aria-hidden="true">&#xf133;</span> Fin estimée : Décembre 2026</span>
              <span><span class="fa-solid" aria-hidden="true">&#xf1ad;</span> Site : IUT Laval</span>
            </div>
          </div>
        </section>

        <main class="main-content">
          <div class="content-grid">
            <div class="primary-column">
              <section class="panel description-panel">
                <h2 class="panel-title"><span class="fa-solid" aria-hidden="true">&#xf036;</span>Description &amp; Objectifs Scientifiques</h2>
                <div class="body-copy">
                  <p><?php echo $projet['desc_proj'] ?></p>
                </div>
              </section>

              <section class="panel documents-panel">
                <div class="panel-title document-heading">
                  <h2><span class="fa-solid" aria-hidden="true">&#xf1c1;</span>Documents &amp; Livrables du Projet</h2>
                  <button class="add-button"><img src="../assets/img/Plus.svg" alt="" />Ajouter</button>
                </div>
                <div class="visibility-box">
                  <strong><span class="fa-solid" aria-hidden="true">&#xf3c1;</span>Visibilité actuelle des livrables :</strong>
                  <select name="visibility">
                    <option selected>Publique (Visible par tout le LIUM)</option>
                    <option>Équipe du projet uniquement</option>
                    <option>Privée</option>
                  </select>
                </div>
                <div class="document-list">
                  <article class="document-row">
                    <div class="document-info">
                      <span class="fa-regular file-icon" aria-hidden="true">&#xf1c1;</span>
                      <span><strong>Protocole_Ergonomique_v2.pdf</strong><small>Ajouté le 10 Septembre 2026 • 2.4 Mo</small></span>
                    </div>
                    <a href="data:text/plain;charset=utf-8,Document%20LIUM%20-%20Protocole_Ergonomique_v2.pdf" download="Protocole_Ergonomique_v2.pdf">
                      <span class="fa-solid" aria-hidden="true">&#xf019;</span> Télécharger
                    </a>
                  </article>

                  <article class="document-row">
                    <div class="document-info">
                      <span class="fa-regular file-icon" aria-hidden="true">&#xf1c2;</span>
                      <span><strong>Objectifs_Recherche.docx</strong><small>Ajouté le 02 Septembre 2026 • 512 Ko</small></span>
                    </div>
                    <a href="data:text/plain;charset=utf-8,Document%20LIUM%20-%20Objectifs_Recherche.docx" download="Objectifs_Recherche.docx">
                      <span class="fa-solid" aria-hidden="true">&#xf019;</span> Télécharger
                    </a>
                  </article>
                </div>
              </section>
            </div>

            <aside class="side-column">
              <section class="info-card terracotta">
                <h2><span class="fa-solid" aria-hidden="true">&#xf085;</span>Logistique (Gérée par le Gestionnaire)</h2>
                <div>
                  <p><span>Salle assignée :</span><strong>Halle Robotique Laval</strong></p>
                  <p><span>Équipement réservé :</span><strong>Robot Porteur H1</strong></p>
                  <p><span>Statut planning :</span><strong>Créneaux OUVERTS</strong></p>
                </div>
              </section>

              <section class="info-card">
                <h2><span class="fa-solid" aria-hidden="true">&#xf0c0;</span>Équipe &amp; Direction</h2>
                <div>
                  <p><span>Chercheur Référent :</span><strong>Dr. L. Oubahssi</strong></p>
                  <p><span>Ingénieur Référent :</span><strong>M. Marc Dupont</strong></p>
                  <p><span>Directeur Labo avisé :</span><strong class="yes"><span class="fa-solid" aria-hidden="true">&#xf00c;</span>Oui</strong></p>
                </div>
              </section>
            </aside>

            <section class="experiments-card">
              <h2>Expérimentations récentes</h2>
              <?php foreach ($exp as $experimentation) {?>
              <article class="experiment-row">
                <span class="experiment-state" style="background: #d0d0d0"></span>
                <span class="experiment-info"><strong><?php echo $experimentation['titre_exp'] ?>
              </strong><small><?php if($experimentation['datefin_exp']<= (new DateTimeImmutable())->format('Y-m-d')){echo "Terminée";}else{echo "En cours";} ?></small></span>
                <time><?= $experimentation['datefin_exp']?></time>
                <a href="ECFicheExp.php?id_exp=<?= urlencode((string) $experimentation['id_exp']) ?>"><button>Voir la fiche <span class="fa-solid" aria-hidden="true">&#xf061;</span></button></a>
              </article>
              
              <?php } ?>

              <!--<article class="experiment-row">
                <span class="experiment-state" style="background: #4761be"></span>
                <span class="experiment-info"><strong>Assistant IA pour l’apprentissage</strong><small>En cours</small></span>
                <time>25-28 sept.</time>
                <button>Voir la fiche <span class="fa-solid" aria-hidden="true">&#xf061;</span></button>
              </article>

              <article class="experiment-row">
                <span class="experiment-state" style="background: #2f6f62"></span>
                <span class="experiment-info"><strong>Corpus multimodal étudiant</strong><small>Terminée</small></span>
                <time>23-25 sept.</time>
                <button>Voir la fiche <span class="fa-solid" aria-hidden="true">&#xf061;</span></button>
              </article>-->
            </section>
          </div>
        </main>
      </div>

      <footer><span class="fa-solid" aria-hidden="true">&#xf19c;</span><strong>Le Mans Université</strong><span>• Fiche détaillée projet</span></footer>
    </div>
  </body>
</html>
