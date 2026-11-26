<?php
include("../model/ECRessourceModel.php");
?>
<!doctype html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Gestion des ressources, salles et modèles types - LIUM Le Mans Université" />
  <title>Ressources - Espace Enseignant-Chercheur · LIUM - Le Mans Université</title>
  <link rel="stylesheet" href="../style/ECRessource.css" />
  <link rel="stylesheet" href="../style/ECheader.css" />
</head>

<body>
  <div class="directeur-ressources-1">
    <div class="background-shadow-2">

      <header class="site-header">
      <button class="brand" aria-label="Retour au tableau de bord">
        <a href="ECDashboard.php"><img src="../assets/img/logoLemansBlancSurBleu.svg" alt="Le Mans Université" /></a>
      </button>

        <nav class="main-navigation" aria-label="Navigation principale">
          <a href="ECDashboard.php"><button type="button" class="nav-link">Dashboard</button></a>
          <a href="ECListProjet.php"><button type="button" class="nav-link">Projets</button></a>
          <a href="ECRessources.php"><button type="button" class="nav-link active">Ressources</button></a>
          <a href="ECMess.php"><button type="button" class="nav-link">Messagerie</button></a>
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
              <a href="?lang=FR" class="dropdown-item selected" role="menuitem">FR <span class="fa">&#xf00c;</span></a>
              <a href="?lang=EN" class="dropdown-item" role="menuitem">EN</a>
              <a href="?lang=ES" class="dropdown-item" role="menuitem">ES</a>
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

          <!-- profil -->
          <div class="menu-anchor profile-anchor">
            <button type="button" class="profile-button" aria-haspopup="menu">
              <span class="avatar"><?= htmlspecialchars($initials ?? 'LO') ?></span>
              <span class="profile-copy">
                <strong><?= htmlspecialchars(($userNom ?? 'Oubahssi') . '.' . ($userPrenom ?? 'L')) ?></strong>
                <small><?= htmlspecialchars($userRole ?? 'Enseignant-Chercheur') ?></small>
              </span>
              <img class="profile-chevron" src="../assets/img/Vector.svg" alt="" />
            </button>
            <div class="dropdown profile-menu" role="menu" style="display: none;">
              <div class="profile-menu-heading">
                <span class="avatar dark"><?= htmlspecialchars($initials ?? 'LO') ?></span>
                <span>
                  <strong><?= htmlspecialchars(($userNom ?? 'Oubahssi') . ' ' . ($userPrenom ?? 'L')) ?></strong>
                  <small><?= htmlspecialchars($userRole ?? 'Enseignant-Chercheur') ?></small>
                </span>
              </div>
              <a href="../controller.php?action=logout" class="dropdown-item danger" role="menuitem"><span class="fa">&#xf2f5;</span>Se déconnecter</a>
            </div>
          </div>
        </div>
      </header>

      <div class="en-t-te-48">
        <div class="container-49">
          <div class="heading-1-50">
            <p class="text-53"><span class="text-white">Gestion des Ressources, Salles &amp; Modèles Types</span></p>
          </div>
          <div class="container-54">
            <p class="text-55"><span class="text-rgb-155-168-189">Affectez les équipements, planifiez les réservations de salles et gérez les documents modèles pour les chercheurs.</span></p>
          </div>
        </div>
      </div>

      <div class="node-60">
        <div class="node-61">
          <div class="quipements-mat-riel-62">
            <div class="horizontalborder-63">
              <div class="paragraph-64">
                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 48 48">
                  <path d="M0 0h48v48H0z" fill="none" />
                  <g fill="none" stroke="rgb(201, 74, 41)" stroke-width="4">
                    <rect width="24" height="36" x="12" y="6" fill="none" rx="2" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 12H6m6 8H6m6 8H6m6 8H6m36-24h-6m6 8h-6m6 8h-6m6 8h-6" />
                  </g>
                </svg>
                <p class="text-66"><span class="text-rgb-29-40-79">Équipements &amp; Matériel</span></p>
              </div>
              <div class="button-67">
                <p class="text-68"><span class="text-white">Ouvrir planning global</span></p>
              </div>
            </div>

            <div class="list-69">
              <?php foreach (get_equipements_materiels() as $materiel) { ?>
                <div class="item-70">
                  <div class="container-71">
                    <div class="background-72">
                      <div class="container-73">
                        <p class="text-74"><span class="text-rgb-29-40-79"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16">
                              <path d="M0 0h16v16H0z" fill="none" />
                              <path fill="currentColor" d="M11 1c.45 0 .882.076 1.286.213L10 3.5l.5 2l2 .5l2.286-2.287a4 4 0 0 1-5.365 4.962l-4.822 5.627A2 2 0 0 1 3.08 15H3a2 2 0 0 1-2-2v-.08a2 2 0 0 1 .698-1.519l5.626-4.823A4 4 0 0 1 11 1M3 12a1 1 0 1 0 0 2a1 1 0 0 0 0-2" />
                            </svg></span></p>
                      </div>
                    </div>
                    <div class="container-75">
                      <p class="text-76"><span class="text-rgb-31-41-55"><?= htmlspecialchars($materiel['nom_ress']) ?></span></p>
                    </div>
                  </div>
                  <div class="container-77">
                    <div class="background-78">
                      <p class="text-79"><span class="text-rgb-180-83-9">Affecté (Projet VR-H1)</span></p>
                    </div>
                    <div class="button-80">
                      <p class="text-81"><span class="text-white">Gérer</span></p>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>

          <div class="node-110">
            <div class="horizontalborder-111">
              <div class="paragraph-112">
                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 32 32">
                  <path d="M0 0h32v32H0z" fill="none" />
                  <path fill="rgb(201,74,41)" d="m15.875 4l-.094.031l-8 1.875L7 6.094v20.25l.813.125l8 1.5l.093.031H18V4zM20 6v2h3v16h-3v2h5V6zm-4 .031V26l-7-1.313V7.657zM14.344 15c-.367 0-.688.45-.688 1s.32 1 .688 1s.656-.45.656-1s-.29-1-.656-1" />
                </svg>
                <p class="text-114"><span class="text-rgb-29-40-79">Salles d'Expérimentation &amp; Plannings</span></p>
              </div>
              <div class="button-115">
                <p class="text-116"><span class="text-white">Ouvrir Planning Global</span></p>
              </div>
            </div>

            <div class="list-117">
              <?php foreach (get_salles() as $salle) { ?>
                <div class="item-118">
                  <div class="container-119">
                    <div class="background-120">
                      <div class="container-121">
                        <p class="text-122"><span class="text-rgb-29-40-79"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                              <path d="M0 0h20v20H0z" fill="none" />
                              <path fill="currentColor" d="M9.186 2.113a.5.5 0 0 1 .419-.1l7 1.499a.5.5 0 0 1 .394.489v12a.5.5 0 0 1-.395.489l-7 1.5A.5.5 0 0 1 9 17.503v-15a.5.5 0 0 1 .186-.389m3.316 7.888a.75.75 0 1 0-1.5 0a.75.75 0 0 0 1.5 0m-4.5-6.998H3.5a.5.5 0 0 0-.5.5v12.995a.5.5 0 0 0 .5.5h4.502z" />
                            </svg></span></p>
                      </div>
                    </div>
                    <div class="container-123">
                      <p class="text-124"><span class="text-rgb-31-41-55"><?= htmlspecialchars($salle['nom_ress']) ?></span></p>
                      <div class="container-125">
                        <p class="text-126"><span class="text-rgb-100-116-139">Le Mans • Bat. Informatique</span></p>
                      </div>
                    </div>
                  </div>
                  <div class="container-127">
                    <div class="background-128">
                      <p class="text-129"><span class="text-rgb-180-83-9">3 Créneaux réservés</span></p>
                    </div>
                    <div class="button-130">
                      <p class="text-131"><span class="text-white">Calendrier</span></p>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>

        <div class="node-160">
          <div class="horizontalborder-161">
            <div class="container-162">
              <div class="heading-2-163">
                <p class="text-166"><span class="text-rgb-29-40-79">Modèles &amp; Documents Types (Bibliothèque Chercheurs)</span></p>
              </div>
              <div class="container-167">
                <p class="text-168"><span class="text-rgb-100-116-139">Documents pré-remplis téléchargeables par les enseignants-chercheurs pour leurs projets.</span></p>
              </div>
            </div>
          </div>

          <div class="container-173">
            <div class="doc-1-rgpd-174">
              <div class="container-175">
                <div class="container-176">
                  <p class="text-177"><span class="text-rgb-220-38-38">&#xf1c1;</span></p>
                  <div class="container-178">
                    <p class="text-179"><span class="text-rgb-29-40-79">Notice RGPD &amp; Consentement Type</span></p>
                    <div class="background-180">
                      <p class="text-181"><span class="text-rgb-37-99-235">Modèle RGPD</span></p>
                    </div>
                  </div>
                </div>
                <div class="container-182">
                  <p class="text-183"><span class="text-rgb-100-116-139">Document d'information pré-validé par le DPO pour la collecte de données auprès de participants volontaires.</span></p>
                </div>
              </div>
              <div class="margin-184">
                <div class="horizontalborder-185">
                  <div class="container-186">
                    <p class="text-187"><span class="text-rgb-148-163-184">PDF • 240 Ko</span></p>
                  </div>
                  <div class="link-188">
                    <p class="text-189"><span class="text-rgb-201-74-41">&#xf019;</span></p>
                    <p class="text-190"><span class="text-rgb-201-74-41">Télécharger</span></p>
                  </div>
                </div>
              </div>
            </div>

            <div class="doc-2-statistiques-191">
              <div class="container-192">
                <div class="container-193">
                  <p class="text-194"><span class="text-rgb-22-163-74">&#xf1c3;</span></p>
                  <div class="container-195">
                    <p class="text-196"><span class="text-rgb-29-40-79">Grille d'Analyse Statistique &amp; Bilan</span></p>
                    <div class="background-197">
                      <p class="text-198"><span class="text-rgb-22-163-74">Modèle Données</span></p>
                    </div>
                  </div>
                </div>
                <div class="container-199">
                  <p class="text-200"><span class="text-rgb-100-116-139">Trame Excel standardisée pour la saisie et l'export anonymisé des résultats d'expérimentation.</span></p>
                </div>
              </div>
              <div class="margin-201">
                <div class="horizontalborder-202">
                  <div class="container-203">
                    <p class="text-204"><span class="text-rgb-148-163-184">XLSX • 115 Ko</span></p>
                  </div>
                  <div class="link-205">
                    <p class="text-206"><span class="text-rgb-201-74-41">&#xf019;</span></p>
                    <p class="text-207"><span class="text-rgb-201-74-41">Télécharger</span></p>
                  </div>
                </div>
              </div>
            </div>

            <div class="node-208">
              <div class="container-209">
                <div class="container-210">
                  <p class="text-211"><span class="text-rgb-37-99-235">&#xf1c2;</span></p>
                  <div class="container-212">
                    <p class="text-213"><span class="text-rgb-29-40-79">Trame Rapport de Résultats d'Expérience</span></p>
                    <div class="background-214">
                      <p class="text-215"><span class="text-rgb-147-51-234">Modèle Rapport</span></p>
                    </div>
                  </div>
                </div>
                <div class="container-216">
                  <p class="text-217"><span class="text-rgb-100-116-139">Trame Word synthétique récapitulant les protocoles, métriques et conclusions pour la direction du labo.</span></p>
                </div>
              </div>
              <div class="margin-218">
                <div class="horizontalborder-219">
                  <div class="container-220">
                    <p class="text-221"><span class="text-rgb-148-163-184">DOCX • 580 Ko</span></p>
                  </div>
                  <div class="link-222">
                    <p class="text-223"><span class="text-rgb-201-74-41">&#xf019;</span></p>
                    <p class="text-224"><span class="text-rgb-201-74-41">Télécharger</span></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="footer-225">
        <div class="container-226">
          <div class="container-229">
            <p class="text-230"><span class="text-rgb-155-168-189">Le Mans Université • Directeur - Ressources</span></p>
          </div>
        </div>
      </div>

    </div>
  </div>
</body>

</html>