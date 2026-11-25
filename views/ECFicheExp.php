<?php 

 include("../controller/ECFicheExpController.php");
 
 ?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $exp['titre_exp'] ?> - Le Mans Université</title>
    <link rel="stylesheet" href="../style/ECFicheExp.css" />
    <link rel="stylesheet" href="../style/ECheader.css" />

  </head>
  <body>
    <div class="app-shell">
      <div class="page">
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

        <section class="hero">
          <div class="hero-content">
            <button class="back-link">
              <a href="ECFicheProj.php?id_proj=<?= get_projet_from_exp($_GET['id_exp'])['id_proj']?>" style="color: inherit; text-decoration: none;"><span class="fa" aria-hidden="true"></span> Retour au projet</a>
            </button>
            <!--<div class="tags">
              <span class="tag subject">Robotique &amp; Ergonomie</span>
              <span class="tag status"><span class="fa" aria-hidden="true"></span> En cours</span>
            </div>-->
            <h1><?= $exp['titre_exp'] ?></h1>
            <div class="hero-meta">
              <!--<span><span class="fa" aria-hidden="true"></span> Responsable : Dr. L. Oubahssi</span>-->
              <span><span class="fa" aria-hidden="true"></span> Début : <?= $exp['datedeb_exp'] ?></span>
              <span><span class="fa" aria-hidden="true"></span> Fin : <?= $exp['datefin_exp'] ?></span>
              <span><span class="fa" aria-hidden="true"></span> Laboratoire : <?= get_lab_from_exp($_GET['id_exp'])['nom_lab'] ?></span>
            </div>
          </div>
          <!--<button class="primary terracotta planning">
            Ouvrir Planning Global
          </button>-->
        </section>

        <main>
          <div class="main-grid">
            <div class="left-column">
              <section class="logistics-card">
                <h2><span class="fa" aria-hidden="true"></span> Logistique</h2>
                <dl>
                  <div>
                    <dt>Salle assignée :</dt>
                    <div>
                      <?php foreach(get_salle_from_exp($_GET['id_exp']) as $ress){ ?>
                        <dd><?= $ress['nom_ress'] ?></dd>
                        <!-- <dd>Halle Robotique Laval</dd> -->
                      
                      <?php } ?>
                    </div>
                    
                  </div>
                  <div>
                    <dt>Équipement réservé :</dt>
                    <div>
                      <?php foreach(get_materiel_from_exp($_GET['id_exp']) as $ress){ ?>
                        <dd><?= $ress['nom_ress'] ?></dd>
                        <!-- <dd>Robot Porteur H1</dd> -->
                      
                      <?php } ?>
                    </div>
                    
                  </div>
                  <!--<div>
                    <dt>Chercheur assigné :</dt>
                    <dd class="researchers">
                      <span>Dr. L. Oubahssi</span>
                      <span>Dr. S. Georges</span>
                    </dd>
                  </div>-->
                  <div>
                    <dt>Nombre de participant</dt>
                    <dd><?= get_nb_participants() ?></dd>
                  </div>
                </dl>
              </section>

              <section class="description card">
                <div class="card-heading">
                  <h2><span class="fa" aria-hidden="true"></span> Description &amp; Objectifs Scientifiques</h2>
                  <button class="secondary-button">
                    Voir les rapports d’activités
                  </button>
                </div>
                <p><?= $exp['desc_exp'] ; ?></p>
                <!--<p>
                  Ce projet étudie l’intégration de robots porteurs humanoïdes dans des environnements de travail
                  industriels ou logistiques.<br />
                  L’objectif principal est de réduire les troubles musculosquelettiques (TMS) liés aux tâches
                  répétitives de portage lourd, sans nécessiter la modification des infrastructures existantes.
                </p>
                <p>
                  Les données biométriques et ergonomiques collectées lors des phases d’expérimentation permettront
                  d’ajuster les algorithmes de suivi de trajectoire et de commande d’effort du robot.
                </p>-->
              </section>

              <section class="incidents">
                <div class="incident-heading">
                  <h2><span class="fa" aria-hidden="true"></span> Suivi des incidents (<?= count(get_incidents_from_exp($_GET['id_exp'])) ?> incident(s) )</h2>
                  <button>Modifier</button>
                </div>
                <?php  foreach (get_incidents_from_exp($_GET['id_exp']) as $incident ) {?>
                  <article class="incident-card">
                  
                  <h3><span class="fa" aria-hidden="true"></span> <?= $incident['titre_incid'] ?></h3>
                  <p><?= $incident['desc_incid'] ?></p>
                </article>
                <!--<article class="incident-card">
                  <div class="date"><span class="fa" aria-hidden="true"></span> 28 sept.</div>
                  <h3><span class="fa" aria-hidden="true"></span> Recharge impossible du Robot Porteur H1</h3>
                  <p>
                    Le rechargement du robot s’est avéré impossible. Après pries en compte des circonstances, le
                    problème vient probablement du doc de charge donc plusieurs broche de contacts se seraient déplacées.
                  </p>
                  <p>Il faudrait une répération dans les 2 jours afin de ne pas prendre de retard sur les expérimentations</p>
                </article>-->
                <?php } ?>
              </section>
            </div>

            <aside class="documents card">
              <div class="card-heading documents-heading">
                <h2><span class="fa" aria-hidden="true"></span> Documents &amp; Livrables du Projet</h2>
                <button class="primary terracotta add-button">
                  <img src="../assets/img/Plus.svg" alt="" /> Ajouter
                </button>
              </div>
              <!--<div class="visibility">
                <span><span class="fa" aria-hidden="true"></span> Visibilité actuelle des livrables :</span>
                <button>Publique</button>
              </div>-->
              <div class="file-list">
                <?php foreach (get_documents_from_exp($_GET['id_exp']) as $doc){ ?>
                  <div class="file-row">
                  <span class="fa fa-regular" aria-hidden="true"></span>
                  <div class="file-copy">
                    <strong><?= $doc['nom_doc'] ?></strong>
                    <small><?= $doc['type_doc'] ?></small>
                  </div>
                  <button><span class="fa" aria-hidden="true"></span> Télécharger</button>
                </div>

                  <?php } ?>
                <!--<div class="file-row">
                  <span class="fa fa-regular" aria-hidden="true"></span>
                  <div class="file-copy">
                    <strong>Protocole_Ergonomique_v2.pdf</strong>
                    <small>Ajouté le 10 Septembre 2026 • 2.4 Mo</small>
                  </div>
                  <button><span class="fa" aria-hidden="true"></span> Télécharger</button>
                </div>

                <div class="file-row">
                  <span class="fa fa-regular" aria-hidden="true"></span>
                  <div class="file-copy">
                    <strong>Notice_Information_RGPD_Participant.docx</strong>
                    <small>Ajouté le 02 Septembre 2026 • 512 Ko</small>
                  </div>
                  <button><span class="fa" aria-hidden="true"></span> Télécharger</button>
                </div>-->

              </div>
              <a href="ECListePart.php?id_exp=<?= $_GET['id_exp'] ?>" style="color: inherit; text-decoration: none;"><button class="participants-button">
                Voir la liste des Participants
              </button></a>
            </aside>
          </div>
        </main>

        <footer>
          <span class="fa" aria-hidden="true"></span>
          <strong>Le Mans Université</strong>
          <span>• EC - Fiche détaillée expérimentation</span>
        </footer>
      </div>
    </div>
  </body>
</html>
