<?php include("../model/DirRessourceModel.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Exported from Figma">
  <title>Exported Figma Design</title>
  <link href="https://fonts.googleapis.com/css?family=Inter|Poppins|Roboto&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../style/DirRessource.css">
</head>

<body>

  <div class="directeur-ressources-1">
    <div class="background-shadow-2">
      <div class="page-aucune">
        <div class="imgLemans">
          <img src="../assets/img/logoLemansBlancSurBleu.png" class="node-47" alt="logo_lemans_universite_blanc-1" />
        </div>
        <div class="node-4">
          <div class="lien-de-navigation-5">
            <p class="text-6"><span class="text-white">Dashboard</span></p>
            <div class="barre-de-visibilit-7"></div>
          </div>
          <div class="lien-de-navigation-8">
            <p class="text-9"><span class="text-white">Projets</span></p>
            <div class="barre-de-visibilit-10"></div>
          </div>
          <div class="lien-de-navigation-11">
            <p class="text-12"><span class="text-white">Ressources</span></p>
            <div class="barre-de-visibilit-13"></div>
          </div>
          <div class="lien-de-navigation-14">
            <p class="text-15"><span class="text-white">Messagerie</span></p>
            <div class="barre-de-visibilit-16"></div>
          </div>
        </div>
        <div class="actions-17">
          <div class="node-18" style="border-radius: 6px">
            <img src="../assets/img/Globe.svg" class="globe-19" alt="globe" />
            <p class="text-20"><span class="text-white">FR</span></p>
            <div class="chevron-down-21">
              <img src="../assets/img/Vector.svg" class="vector-22" alt="vector" />
            </div>
          </div>
          <div class="node-23">
            <div class="notification-trigger-24">
              <div class="bell-25">
                <img src="../assets/img/bell.svg" class="vector-26" alt="vector" />
              </div>
            </div>
          </div>
          <div class="tat-tat2">
            <div class="contr-le-de-profil-28">
              <div class="avatar-40">
                <p class="text-41"><span class="text-white">LO</span></p>
              </div>
              <div class="node-42">
                <p class="text-43"><span class="text-white">Dr. L. Oubahssi</span></p>
                <p class="text-44"><span class="text-white">Enseignant-Chercheur</span></p>
              </div>
              <div class="chevron-up-45">
                <img src="../assets/img/Vector.svg" class="vector-46" alt="vector" />
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="en-t-te-48">
        <div class="container-49">
          <div class="heading-1-50">
            <p class="text-53"><span class="text-white">Gestion des Ressources, Salles &amp; Modèles Types</span></p>
          </div>
          <div class="container-54">
            <p class="text-55"><span class="text-rgb-155-168-189">Affectez les équipements, planifiez les réservations
                de salles et gérez les documents modèles pour les chercheurs.</span></p>
          </div>
        </div>
        <div class="button-56">
          <div class="container-57">
            <p class="text-58"><span class="text-white">+</span></p>
          </div>
          <p class="text-59"><span class="text-white">Ajouter un équipement / salle</span></p>
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
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 12H6m6 8H6m6 8H6m6 8H6m36-24h-6m6 8h-6m6 8h-6m6 8h-6" />
                  </g>
                </svg>
                <p class="text-66"><span class="text-rgb-29-40-79"> Équipements &amp; Matériel</span></p>
              </div>
              <div class="button-67">
                <p class="text-68"><span class="text-white">+ Nouveau matériel</span></p>
              </div>
            </div>

            <div class="list-69">
              <?php
              foreach(get_equipements_materiels() as $materiel) {
                
              ?>
              <div class="item-70">
                <div class="container-71">
                  <div class="background-72">
                    <div class="container-73">
                      <p class="text-74"><span class="text-rgb-29-40-79"></span></p>
                    </div>
                  </div>
                  <div class="container-75">
                    <p class="text-76"><span class="text-rgb-31-41-55"><?php echo $materiel['nom_ress'] ;?>
  
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
              <!--
              <div class="item-82">
                <div class="container-83">
                  <div class="background-84">
                    <div class="container-85">
                      <p class="text-86"><span class="text-rgb-29-40-79"></span></p>
                    </div>
                  </div>
                  <div class="container-87">
                    <p class="text-88"><span class="text-rgb-31-41-55">Lot 5 Casques Meta Quest 3</span></p>
                    <div class="container-89">
                      <p class="text-90"><span class="text-rgb-100-116-139">Le Mans • Labo IHM</span></p>
                    </div>
                  </div>
                </div>
                <div class="container-91">
                  <div class="background-92">
                    <p class="text-93"><span class="text-rgb-21-128-61">Disponible</span></p>
                  </div>
                  <div class="button-94">
                    <p class="text-95"><span class="text-white">Affecter</span></p>
                  </div>
                </div>
              </div>

              <div class="item-96">
                <div class="container-97">
                  <div class="background-98">
                    <div class="container-99">
                      <p class="text-100"><span class="text-rgb-29-40-79"></span></p>
                    </div>
                  </div>
                  <div class="container-101">
                    <p class="text-102"><span class="text-rgb-31-41-55">Capteurs Biométriques Empatica</span></p>
                    <div class="container-103">
                      <p class="text-104"><span class="text-rgb-100-116-139">Le Mans • Stock Central</span></p>
                    </div>
                  </div>
                </div>
                <div class="container-105">
                  <div class="background-106">
                    <p class="text-107"><span class="text-rgb-21-128-61">Disponible</span></p>
                  </div>
                  <div class="button-108">
                    <p class="text-109"><span class="text-white">Affecter</span></p>
                  </div>
                </div>
              </div>-->
            </div>


          </div>
          <div class="node-110">
            <div class="horizontalborder-111">
              <div class="paragraph-112">
                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 32 32">
                  <path d="M0 0h32v32H0z" fill="none" />
                  <path fill="rgb(201,74,41)"
                    d="m15.875 4l-.094.031l-8 1.875L7 6.094v20.25l.813.125l8 1.5l.093.031H18V4zM20 6v2h3v16h-3v2h5V6zm-4 .031V26l-7-1.313V7.657zM14.344 15c-.367 0-.688.45-.688 1s.32 1 .688 1s.656-.45.656-1s-.29-1-.656-1" />
                </svg>
                <p class="text-114"><span class="text-rgb-29-40-79"> Salles d'Expérimentation &amp; Plannings</span></p>
              </div>
              <div class="button-115">
                <p class="text-116"><span class="text-white">Ouvrir Planning Global</span></p>
              </div>
            </div>
            <div class="list-117">
              <?php foreach(get_salles() as $salle) { ?>

              <div class="item-118">
                <div class="container-119">
                  <div class="background-120">
                    <div class="container-121">
                      <p class="text-122"><span class="text-rgb-29-40-79"></span></p>
                    </div>
                  </div>
                  <div class="container-123">
                    <p class="text-124"><span class="text-rgb-31-41-55"><?php echo $salle['nom_ress']; ?></span></p>
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
              <!--
              <div class="item-132">
                <div class="container-133">
                  <div class="background-134">
                    <div class="container-135">
                      <p class="text-136"><span class="text-rgb-29-40-79"></span></p>
                    </div>
                  </div>
                  <div class="container-137">
                    <p class="text-138"><span class="text-rgb-31-41-55">Halle d'Essais Robotiques</span></p>
                    <div class="container-139">
                      <p class="text-140"><span class="text-rgb-100-116-139">Laval • Génie Mécanique</span></p>
                    </div>
                  </div>
                </div>
                <div class="container-141">
                  <div class="background-142">
                    <p class="text-143"><span class="text-rgb-21-128-61">Libre cette semaine</span></p>
                  </div>
                  <div class="button-144">
                    <p class="text-145"><span class="text-white">Calendrier</span></p>
                  </div>
                </div>
              </div>
              <div class="item-146">
                <div class="container-147">
                  <div class="background-148">
                    <div class="container-149">
                      <p class="text-150"><span class="text-rgb-29-40-79"></span></p>
                    </div>
                  </div>
                  <div class="container-151">
                    <p class="text-152"><span class="text-rgb-31-41-55">Box de Test Utilisateur A &amp; B</span></p>
                    <div class="container-153">
                      <p class="text-154"><span class="text-rgb-100-116-139">Le Mans • RDC</span></p>
                    </div>
                  </div>
                </div>
                <div class="container-155">
                  <div class="background-156">
                    <p class="text-157"><span class="text-rgb-21-128-61">Libre</span></p>
                  </div>
                  <div class="button-158">
                    <p class="text-159"><span class="text-white">Calendrier</span></p>
                  </div>
                </div>
              </div>-->

            </div>
          </div>
        </div>
        <div class="node-160">
          <div class="horizontalborder-161">
            <div class="container-162">
              <div class="heading-2-163">
                <p class="text-166"><span class="text-rgb-29-40-79">Modèles &amp; Documents Types (Bibliothèque
                    Chercheurs)</span></p>
              </div>
              <div class="container-167">
                <p class="text-168"><span class="text-rgb-100-116-139">Documents pré-remplis téléchargeables par les
                    enseignants-chercheurs pour leurs projets.</span></p>
              </div>
            </div>
            <div class="button-169">
              <p class="text-172"><span class="text-rgb-201-74-41">Déposer un nouveau modèle</span></p>
            </div>
          </div>
          <div class="container-173">

            <div class="doc-1-rgpd-174">
              <div class="container-175">
                <div class="container-176">
                  <p class="text-177"><span class="text-rgb-220-38-38"></span></p>
                  <div class="container-178">
                    <p class="text-179"><span class="text-rgb-29-40-79">Notice RGPD &amp; Consentement Type</span></p>
                    <div class="background-180">
                      <p class="text-181"><span class="text-rgb-37-99-235">Modèle RGPD</span></p>
                    </div>
                  </div>
                </div>
                <div class="container-182">
                  <p class="text-183"><span class="text-rgb-100-116-139">Document d'information pré-validé par le DPO
                      pour la collecte de
                      données auprès de participants volontaires.</span></p>
                </div>
              </div>
              <div class="margin-184">
                <div class="horizontalborder-185">
                  <div class="container-186">
                    <p class="text-187"><span class="text-rgb-148-163-184">PDF • 240 Ko</span></p>
                  </div>
                  <div class="link-188">
                    <p class="text-189"><span class="text-rgb-201-74-41"></span></p>
                    <p class="text-190"><span class="text-rgb-201-74-41"> Télécharger</span></p>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="doc-2-statistiques-191">
              <div class="container-192">
                <div class="container-193">
                  <p class="text-194"><span class="text-rgb-22-163-74"></span></p>
                  <div class="container-195">
                    <p class="text-196"><span class="text-rgb-29-40-79">Grille d'Analyse Statistique &amp; Bilan</span>
                    </p>
                    <div class="background-197">
                      <p class="text-198"><span class="text-rgb-22-163-74">Modèle Données</span></p>
                    </div>
                  </div>
                </div>
                <div class="container-199">
                  <p class="text-200"><span class="text-rgb-100-116-139">Trame Excel standardisée pour la saisie et
                      l'export anonymisé des
                      résultats d'expérimentation.</span></p>
                </div>
              </div>
              <div class="margin-201">
                <div class="horizontalborder-202">
                  <div class="container-203">
                    <p class="text-204"><span class="text-rgb-148-163-184">XLSX • 115 Ko</span></p>
                  </div>
                  <div class="link-205">
                    <p class="text-206"><span class="text-rgb-201-74-41"></span></p>
                    <p class="text-207"><span class="text-rgb-201-74-41"> Télécharger</span></p>
                  </div>
                </div>
              </div>
            </div>
            <div class="node-208">
              <div class="container-209">
                <div class="container-210">
                  <p class="text-211"><span class="text-rgb-37-99-235"></span></p>
                  <div class="container-212">
                    <p class="text-213"><span class="text-rgb-29-40-79">Trame Rapport de Résultats d'Expérience</span>
                    </p>
                    <div class="background-214">
                      <p class="text-215"><span class="text-rgb-147-51-234">Modèle Rapport</span></p>
                    </div>
                  </div>
                </div>
                <div class="container-216">
                  <p class="text-217"><span class="text-rgb-100-116-139">Trame Word synthétique récapitulant les
                      protocoles, métriques et
                      conclusions pour la direction du labo.</span></p>
                </div>
              </div>
              <div class="margin-218">
                <div class="horizontalborder-219">
                  <div class="container-220">
                    <p class="text-221"><span class="text-rgb-148-163-184">DOCX • 580 Ko</span></p>
                  </div>
                  <div class="link-222">
                    <p class="text-223"><span class="text-rgb-201-74-41"></span></p>
                    <p class="text-224"><span class="text-rgb-201-74-41"> Télécharger</span></p>
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
            <p class="text-230"><span class="text-rgb-155-168-189">Le Mans Université • Directeur - Ressources</span>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>

</html>