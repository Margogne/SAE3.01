<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Gestion des participants — Le Mans Université" />
    <title>Gestion des participants</title>
    <link rel="stylesheet" href="../style/ECListePart.css" />
  </head>
  <body>
    <div class="app-shell">
      <?php
        include "../model/ECListePartModel.php";
        include '../model/ECHeaderModel.php';
      ?>

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
        <?php if(count(get_participants_from_exp($_GET['id_exp']))==0){
          echo 'Aucun participant';
        } else{ echo count(get_participants_from_exp($_GET['id_exp'])).' Participant(s)'?>
        <div class="participant-grid">
          <section class="participant-column">
            <h2><img src="/assets/ea0ca.svg" alt="" />Inscrits</h2>
            <div class="table-head registered">
              <span>Nom</span><span>Prénom</span><span>Date d’inscription</span>
            </div>
            <div class="participant-list">
              
              
              <!-- Louka, prend juste lui comme modele -->
               <?php foreach(get_participants_from_exp($_GET['id_exp']) as $part) {?>
              <article class="participant-row registered">
                <div class="participant-data"><strong><?= $part['nom_util'] ?></strong><span><?= $part['prenom_util'] ?></span><!--<span>10 septembre 2026</span>--></div>
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
