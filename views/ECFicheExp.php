<?php 

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ID.php");
    exit();
}

$userNom = $_SESSION['user_nom'] ?? 'Utilisateur';
$userPrenom = $_SESSION['user_prenom'] ?? '';
$userRole = $_SESSION['user_role'] ?? 'Enseignant chercheur';
$initials = strtoupper(mb_substr($userPrenom, 0, 1) . mb_substr($userNom, 0, 1)) ?: 'U';

include("../model/ECDashboardModel.php") ?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fiche détaillée expérimentation - Robot Porteur Humanoïde - Le Mans Université</title>
    <link rel="stylesheet" href="../style/ECFicheExp.css.css" />
  </head>
  <body>
    <div class="app-shell">
      <div class="page">
        <?php
        include '../model/ECHeaderModel.php';
        ?>

        <section class="hero">
          <div class="hero-content">
            <button class="back-link">
              <span class="fa" aria-hidden="true"></span> Retour au projet
            </button>
            <div class="tags">
              <span class="tag subject">Robotique &amp; Ergonomie</span>
              <span class="tag status"><span class="fa" aria-hidden="true"></span> En cours</span>
            </div>
            <h1>Robot Porteur Humanoïde — Assistance physique sans infrastructure</h1>
            <div class="hero-meta">
              <span><span class="fa" aria-hidden="true"></span> Responsable : Dr. L. Oubahssi</span>
              <span><span class="fa" aria-hidden="true"></span> Début : 1 Septembre 2026</span>
              <span><span class="fa" aria-hidden="true"></span> Fin : 24 Décembre 2026</span>
              <span><span class="fa" aria-hidden="true"></span> Site : IUT Laval</span>
            </div>
          </div>
          <button class="primary terracotta planning">
            Ouvrir Planning Global
          </button>
        </section>

        <main>
          <div class="main-grid">
            <div class="left-column">
              <section class="logistics-card">
                <h2><span class="fa" aria-hidden="true"></span> Logistique</h2>
                <dl>
                  <div>
                    <dt>Salle assignée :</dt>
                    <dd>Halle Robotique Laval</dd>
                  </div>
                  <div>
                    <dt>Équipement réservé :</dt>
                    <dd>Robot Porteur H1</dd>
                  </div>
                  <div>
                    <dt>Chercheur assigné :</dt>
                    <dd class="researchers">
                      <span>Dr. L. Oubahssi</span>
                      <span>Dr. S. Georges</span>
                    </dd>
                  </div>
                  <div>
                    <dt>Nombre de participant</dt>
                    <dd>12/14</dd>
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
                <p>
                  Ce projet étudie l’intégration de robots porteurs humanoïdes dans des environnements de travail
                  industriels ou logistiques.<br />
                  L’objectif principal est de réduire les troubles musculosquelettiques (TMS) liés aux tâches
                  répétitives de portage lourd, sans nécessiter la modification des infrastructures existantes.
                </p>
                <p>
                  Les données biométriques et ergonomiques collectées lors des phases d’expérimentation permettront
                  d’ajuster les algorithmes de suivi de trajectoire et de commande d’effort du robot.
                </p>
              </section>

              <section class="incidents">
                <div class="incident-heading">
                  <h2><span class="fa" aria-hidden="true"></span> Suivi des incidents (1 incident reporté)</h2>
                  <button>Modifier</button>
                </div>
                <article class="incident-card">
                  <div class="date"><span class="fa" aria-hidden="true"></span> 28 sept.</div>
                  <h3><span class="fa" aria-hidden="true"></span> Recharge impossible du Robot Porteur H1</h3>
                  <p>
                    Le rechargement du robot s’est avéré impossible. Après pries en compte des circonstances, le
                    problème vient probablement du doc de charge donc plusieurs broche de contacts se seraient déplacées.
                  </p>
                  <p>Il faudrait une répération dans les 2 jours afin de ne pas prendre de retard sur les expérimentations</p>
                </article>
              </section>
            </div>

            <aside class="documents card">
              <div class="card-heading documents-heading">
                <h2><span class="fa" aria-hidden="true"></span> Documents &amp; Livrables du Projet</h2>
                <button class="primary terracotta add-button">
                  <img src="../assets/img/Plus.svg" alt="" /> Ajouter
                </button>
              </div>
              <div class="visibility">
                <span><span class="fa" aria-hidden="true"></span> Visibilité actuelle des livrables :</span>
                <button>Publique</button>
              </div>
              <div class="file-list">
                <div class="file-row">
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
                </div>
              </div>
              <button class="participants-button">
                Voir la liste des Participants
              </button>
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
