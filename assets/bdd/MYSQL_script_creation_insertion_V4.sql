DROP DATABASE IF EXISTS Lab_Project_GRP04;
CREATE DATABASE Lab_Project_GRP04;
USE Lab_Project_GRP04;

CREATE TABLE RESSOURCE(
   id_ress INT AUTO_INCREMENT,
   nom_ress VARCHAR(50),
   desc_ress VARCHAR(400),
   PRIMARY KEY(id_ress)
);

CREATE TABLE UTILISATEUR(
   id_util INT AUTO_INCREMENT,
   nom_util VARCHAR(50),
   prenom_util VARCHAR(50),
   type_util VARCHAR(50) CHECK (type_util IN ('admin', 'directeur', 'enseignant chercheur', 'externe')),
   date_util DATE,
   genre_util VARCHAR(50),
   langues_util VARCHAR(50),
   contr_util VARCHAR(200),
   spec_util VARCHAR(1500),
   PRIMARY KEY(id_util)
);

CREATE TABLE LABORATOIRE(
   id_lab INT AUTO_INCREMENT,
   nom_lab VARCHAR(100),
   ville_lab VARCHAR(50),
   id_util INT NOT NULL,
   PRIMARY KEY(id_lab),
   UNIQUE(id_util),
   FOREIGN KEY(id_util) REFERENCES UTILISATEUR(id_util)
);

CREATE TABLE PROJET(
   id_proj INT AUTO_INCREMENT,
   nom_proj VARCHAR(50),
   desc_proj VARCHAR(400),
   datedeb_proj DATE,
   datefin_proj DATE,
   id_lab INT NOT NULL,
   PRIMARY KEY(id_proj),
   FOREIGN KEY(id_lab) REFERENCES LABORATOIRE(id_lab)
);

CREATE TABLE EXPERIMENTATION(
   id_exp INT AUTO_INCREMENT,
   titre_exp VARCHAR(50),
   desc_exp VARCHAR(400),
   datefin_exp DATE,
   dur_exp VARCHAR(50),
   retrib_exp VARCHAR(50),
   datedeb_exp DATE,
   id_proj INT,
   PRIMARY KEY(id_exp),
   FOREIGN KEY(id_proj) REFERENCES PROJET(id_proj)
);

CREATE TABLE SESSION(
   id_sess INT AUTO_INCREMENT,
   datedeb_sess DATETIME,
   datefin_sess DATETIME,
   statut_sess VARCHAR(50),
   id_exp INT NOT NULL,
   PRIMARY KEY(id_sess),
   FOREIGN KEY(id_exp) REFERENCES EXPERIMENTATION(id_exp)
);

CREATE TABLE INCIDENT(
   id_incid INT AUTO_INCREMENT,
   desc_incid VARCHAR(400),
   titre_incid VARCHAR(50),
   id_sess INT NOT NULL,
   PRIMARY KEY(id_incid),
   FOREIGN KEY(id_sess) REFERENCES SESSION(id_sess)
);

CREATE TABLE DOCUMENT(
   id_doc INT AUTO_INCREMENT,
   nom_doc VARCHAR(50),
   type_doc VARCHAR(50),
   id_exp INT NOT NULL,
   PRIMARY KEY(id_doc),
   FOREIGN KEY(id_exp) REFERENCES EXPERIMENTATION(id_exp)
);

CREATE TABLE TRAVAILLER(
   id_exp INT,
   id_util INT,
   num_ordre VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_exp, id_util),
   FOREIGN KEY(id_exp) REFERENCES EXPERIMENTATION(id_exp),
   FOREIGN KEY(id_util) REFERENCES UTILISATEUR(id_util)
);

CREATE TABLE CONTENIR(
   id_sess INT,
   id_ress INT,
   num_attribution VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_sess, id_ress),
   FOREIGN KEY(id_sess) REFERENCES SESSION(id_sess),
   FOREIGN KEY(id_ress) REFERENCES RESSOURCE(id_ress)
);

CREATE TABLE ECHANGER(
   id_util_receveur INT,
   id_util_expéditeur INT,
   date_envoi DATETIME,
   id_mess INT AUTO_INCREMENT,
   contenu_mess VARCHAR(5000),
   status_mess  BOOLEAN NOT NULL DEFAULT FALSE,
   PRIMARY KEY(id_util_receveur, id_util_expéditeur, date_envoi),
   UNIQUE(id_mess),
   FOREIGN KEY(id_util_receveur) REFERENCES UTILISATEUR(id_util),
   FOREIGN KEY(id_util_expéditeur) REFERENCES UTILISATEUR(id_util)
);

CREATE TABLE REFERENCER(
   id_proj INT,
   id_util INT,
   id_role INT AUTO_INCREMENT,
   nom_role VARCHAR(50) CHECK (nom_role IN ('membre', 'gestionnaire', 'responsable', 'participant')),
   PRIMARY KEY(id_proj, id_util),
   UNIQUE(id_role),
   FOREIGN KEY(id_proj) REFERENCES PROJET(id_proj),
   FOREIGN KEY(id_util) REFERENCES UTILISATEUR(id_util)
);

CREATE TABLE PARTICIPER(
   id_sess INT,
   id_util INT,
   PRIMARY KEY(id_sess, id_util),
   FOREIGN KEY(id_sess) REFERENCES SESSION(id_sess),
   FOREIGN KEY(id_util) REFERENCES UTILISATEUR(id_util)
);

CREATE TABLE RAPPORT(
   id_rap INT AUTO_INCREMENT,
   titre_rap VARCHAR(100) NOT NULL,
   nbparticipant_rap INT NOT NULL DEFAULT 0,
   nbincid_rap INT NOT NULL DEFAULT 0,
   desc_rap VARCHAR(1500),
   date_rap DATE NOT NULL,
   id_exp INT NOT NULL,
   PRIMARY KEY(id_rap),
   FOREIGN KEY(id_exp) REFERENCES EXPERIMENTATION(id_exp)
);

CREATE TABLE RESERVATION (
   id_res INT AUTO_INCREMENT,
   datedeb_res DATETIME NOT NULL,
   datefin_res DATETIME NOT NULL,
   id_sess INT NOT NULL,
   id_ress INT NOT NULL,
   PRIMARY KEY (id_res),
   FOREIGN KEY (id_sess) REFERENCES SESSION(id_sess)
      ON DELETE CASCADE ON UPDATE CASCADE,
   FOREIGN KEY (id_ress) REFERENCES RESSOURCE(id_ress)
      ON DELETE CASCADE ON UPDATE CASCADE,
   CHECK (datefin_res > datedeb_res)
);

-- Insertion des données

INSERT INTO RESSOURCE (id_ress, nom_ress, desc_ress) VALUES
(1, 'Microscope Électronique', 'Matériel - IMMM : Microscope haute résolution pour analyse nanométrique.'),
(2, 'Spectromètre de Masse', 'Matériel - IMMM : Appareil d''analyse chimique et moléculaire.'),
(3, 'Supercalculateur Nœud A', 'Matériel - LIUM : Ressource de calcul intensif pour IA et modélisation.'),
(4, 'Oscilloscope Numérique', 'Matériel - LAUM : Outil de mesure pour signaux acoustiques et électriques.'),
(5, 'Salle de Réunion A101', 'Salle - Bâtiment A : Salle de réunion équipée pour visioconférence.'),
(6, 'Amphithéâtre Euler', 'Salle - Bâtiment Mathématiques : Grand amphithéâtre pour séminaires et colloques.'),
(7, 'Salle TP Informatique B202', 'Salle - Bâtiment B : Salle informatique dédiée aux travaux pratiques.'),
(8, 'Agitateur Magnétique', 'Matériel - Chimie : Dispositif de mélange de solutions en continu.'),
(9, 'Salle de Conférence Themis', 'Salle - Bâtiment Droit : Espace de réception et de colloques.'),
(10, 'Cuve Acoustique Immergée', 'Matériel - LAUM : Cuve de test pour capteurs subaquatiques.'),
(11, 'Salle de Réunion B104', 'Salle - Bâtiment B : Salle de réunion de 16 places avec écran interactif.'),
(12, 'Salle Projet C302', 'Salle - Bâtiment C : Espace collaboratif pour réunions d''équipe et ateliers.'),
(13, 'Amphithéâtre Langevin', 'Salle - Bâtiment Physique : Amphithéâtre pour conférences et soutenances.'),
(14, 'Salle d''Expérimentation 101', 'Salle - Bâtiment Recherche : Salle isolée acoustiquement pour tests utilisateurs.'),
(15, 'Salle d''Observation 102', 'Salle - Bâtiment Recherche : Salle vitrée adjacente pour suivi et prise de notes.'),
(16, 'Salle Séminaire D205', 'Salle - Bâtiment Lettres : Salle équipée pour séminaires et présentations.'),
(17, 'Station de Calcul GPU A', 'Matériel - LIUM : Serveur équipé de cartes graphiques pour entraînement de modèles IA.'),
(18, 'Station de Calcul GPU B', 'Matériel - LIUM : Serveur dédié aux simulations numériques et traitements lourds.'),
(19, 'Caméra Haute Vitesse', 'Matériel - LAUM : Caméra rapide pour capturer des phénomènes physiques brefs.'),
(20, 'Générateur de Signaux', 'Matériel - LAUM : Équipement de génération de signaux précis pour tests électroniques.'),
(21, 'Analyseur de Spectre', 'Matériel - LAUM : Instrument de mesure pour l''analyse fréquentielle des signaux.'),
(22, 'Microscope Optique Binoculaire', 'Matériel - IMMM : Microscope pour observation détaillée d''échantillons biologiques ou matériaux.'),
(23, 'Balance de Précision', 'Matériel - Chimie : Balance analytique haute précision pour pesées de laboratoire.'),
(24, 'Centrifugeuse de Paillasse', 'Matériel - Chimie : Appareil de séparation par centrifugation pour échantillons liquides.'),
(25, 'Étuve de Séchage', 'Matériel - IMMM : Enceinte thermique contrôlée pour séchage et essais de stabilité.'),
(26, 'Casque Réalité Virtuelle Pro', 'Matériel - LIUM : Casque VR pour expérimentations d''interaction et immersion.'),
(27, 'Eye-Tracker Fixe', 'Matériel - LIUM : Capteur de suivi du regard pour études ergonomiques et cognitives.'),
(28, 'Table Tactile Interactive', 'Matériel - LIUM : Écran tactile grand format pour tests d''interfaces collaboratives.'),
(29, 'Enregistreur Audio Multipiste', 'Matériel - LAUM : Enregistreur numérique pour captation audio haute fidélité.'),
(30, 'Capteur de Pression Subaquatique', 'Matériel - LAUM : Capteur étanche pour mesures en milieu aquatique.'),
(31, 'Videoprojecteur Mobile 4K', 'Matériel - Mutualisé : Projecteur portable pour démonstrations et réunions.'),
(32, 'Kit Micros & Enceintes Conférence', 'Matériel - Mutualisé : Système de sonorisation mobile pour salles de réunion.');

INSERT INTO UTILISATEUR (id_util, nom_util, prenom_util, type_util, date_util, genre_util, langues_util, contr_util) VALUES
(1, 'Durand', 'Pierre', 'admin', '2018-01-15', 'M', 'Français, Anglais', 'CDI Temps Plein'),
(2, 'Martin', 'Hélène', 'directeur', '2019-09-01', 'F', 'Français, Anglais', 'Statutaire Fonction Publique'),
(3, 'Bernard', 'Luc', 'enseignant chercheur', '2021-06-10', 'M', 'Français', 'CDI Temps Plein'),
(4, 'Thomas', 'Sophie', 'enseignant chercheur', '2019-09-01', 'F', 'Français, Anglais', 'Statutaire Fonction Publique'),
(5, 'Petit', 'Marc', 'enseignant chercheur', '2020-03-12', 'M', 'Français, Anglais', 'CDI Temps Plein'),
(6, 'Robert', 'Emma', 'enseignant chercheur', '2024-01-10', 'F', 'Anglais, Français', 'Contrat Temporaire Recherche'),
(7, 'Moreau', 'Alexandre', 'externe', '2022-05-20', 'M', 'Français', 'Prestataire Externe'),
(8, 'Simon', 'Chloé', 'externe', '2023-02-01', 'F', 'Français, Anglais', 'Chercheur Invité Partenaire'),
(9, 'Leroy', 'Antoine', 'externe', '2023-10-15', 'M', 'Français, Allemand', 'Consultant Industriel'),
(10, 'Gauthier', 'Camille', 'externe', '2024-03-01', 'F', 'Français', 'Partenaire Académique Étranger'),
(11, 'J', 'John', 'externe', '2007-04-02', 'M', 'Français', 'compte test maxime');

INSERT INTO LABORATOIRE (id_lab, nom_lab, ville_lab, id_util) VALUES
(1, 'ESO : Espaces et Sociétés', 'Le Mans', 1),
(2, 'GAINS : Économie', 'Le Mans', 2),
(3, 'IMMM : Institut des Molécules et des Matériaux du Mans', 'Le Mans', 3),
(4, 'LAUM : Laboratoire d''Acoustique de l''Université du Mans', 'Le Mans', 4),
(5, 'LIUM : Laboratoire d''Informatique de l''Université du Mans', 'Le Mans', 5),
(6, 'LMM : Laboratoire Manceau de Mathématiques', 'Le Mans', 6),
(7, 'LPG : Laboratoire de Planétologie et Géosciences', 'Le Mans', 7),
(8, 'MIP : Motricité, Interactions, Performance', 'Le Mans', 8),
(9, 'TEMOS : Temps, Mondes, Sociétés', 'Le Mans', 9),
(10, 'Themis-UM : Droit', 'Le Mans', 10);

INSERT INTO PROJET (id_proj, nom_proj, desc_proj, datedeb_proj, datefin_proj, id_lab) VALUES
(1, 'Reconnaissance Vocale Avancée', 'Développement de modèles de Deep Learning pour le LIUM.', '2024-01-10', '2026-12-31', 5),
(2, 'Caractérisation Acoustique Matériaux', 'Étude de l''absorption phonique au LAUM.', '2023-05-15', '2025-05-15', 4),
(3, 'Nanomatériaux Polymères', 'Synthèse et analyse de nouveaux polymères à l''IMMM.', '2024-03-01', '2027-03-01', 3),
(4, 'Modélisation Stochastique', 'Recherche en mathématiques appliquées au LMM.', '2024-09-01', '2028-08-31', 6),
(5, 'Dynamique des Inégalités Salariales', 'Analyse socio-économique menée par le GAINS.', '2023-10-01', '2025-10-01', 2),
(6, 'Traduction Automatique Contextuelle', 'Projet de TAL au sein du LIUM.', '2024-02-15', '2026-02-14', 5),
(7, 'Métamatériaux Sonores', 'Conception de structures acoustiques innovantes au LAUM.', '2022-01-01', '2025-12-31', 4),
(8, 'Synthèse Verte de Catalyseurs', 'Chimie durable et catalyse à l''IMMM.', '2023-11-15', '2026-11-14', 3),
(9, 'Équations aux Dérivées Partielles', 'Études théoriques au LMM.', '2024-06-01', '2026-06-01', 6),
(10, 'Analyse des Marchés Émergents', 'Étude économétrique du GAINS.', '2025-01-01', '2027-12-31', 2);

INSERT INTO EXPERIMENTATION (id_exp, titre_exp, desc_exp, datefin_exp, dur_exp, retrib_exp, datedeb_exp, id_proj) VALUES
(1, 'Test Modèle Transformers', 'Benchmark de performance sur le LIUM.', '2024-04-10', '3 mois', '500 EUR', '2024-01-15', 1),
(2, 'Mesure Coefficient Absorption', 'Test en tube de Kundt au LAUM.', '2023-07-20', '2 mois', '300 EUR', '2023-05-20', 2),
(3, 'Spectroscopie Infrarouge', 'Analyse structurelle de polymères à l''IMMM.', '2024-06-30', '4 mois', '450 EUR', '2024-03-01', 3),
(4, 'Simulation Monte-Carlo', 'Calculs intensifs de probabilités au LMM.', '2024-12-15', '6 mois', '800 EUR', '2024-09-01', 4),
(5, 'Analyse Panel Ménages', 'Traitement statistique des données GAINS.', '2024-02-28', '1 mois', '200 EUR', '2024-01-10', 5),
(6, 'Évaluation BLEU Score', 'Tests de traduction automatique.', '2024-08-30', '5 mois', '600 EUR', '2024-03-15', 6),
(7, 'Test Plaque Résonante', 'Mesures de transmission acoustique.', '2023-12-20', '12 mois', '1200 EUR', '2023-01-01', 7),
(8, 'Rendement Catalytique', 'Test d''efficacité en réacteur.', '2024-10-15', '3 mois', '400 EUR', '2024-07-01', 8),
(9, 'Résolution Numérique EDP', 'Implémentation d''algorithmes au LMM.', '2024-05-15', '2 mois', '350 EUR', '2024-03-15', 9),
(10, 'Étude Économétrique Emploi', 'Modélisation des flux de salaires.', '2025-04-30', '3 mois', '500 EUR', '2025-02-01', 10);

INSERT INTO SESSION (id_sess, datedeb_sess, datefin_sess, statut_sess, id_exp) VALUES
(1, '2024-01-15 08:30:00', '2024-01-15 17:30:00', 'Terminé', 1),
(2, '2023-05-20 09:00:00', '2023-05-20 16:00:00', 'Terminé', 2),
(3, '2024-03-02 10:00:00', '2024-03-02 18:00:00', 'Terminé', 3),
(4, '2024-09-05 14:00:00', '2024-09-05 23:59:59', 'En cours', 4),
(5, '2024-01-12 08:00:00', '2024-01-12 12:00:00', 'Terminé', 5),
(6, '2024-03-20 09:30:00', '2024-03-20 17:00:00', 'En cours', 6),
(7, '2023-02-10 08:00:00', '2023-02-10 20:00:00', 'Terminé', 7),
(8, '2024-07-05 13:00:00', '2024-07-05 17:00:00', 'Terminé', 8),
(9, '2024-03-18 09:00:00', '2024-03-18 15:00:00', 'Terminé', 9),
(10, '2025-02-05 07:00:00', '2025-02-05 19:00:00', 'Planifié', 10);

INSERT INTO INCIDENT (id_incid, desc_incid, titre_incid, id_sess) VALUES
(1, 'Saturation mémoire GPU lors de l''entraînement.', 'Surchauffe GPU', 1),
(2, 'Bruit parasite capté par le microphone de mesure.', 'Parasite Acoustique', 2),
(3, 'Fluctuation de température dans le four de synthèse.', 'Décadrage Thermique', 3),
(4, 'Dépassement de pile lors de la simulation stochastique.', 'Erreur Mémoire', 4),
(5, 'Valeurs manquantes dans le fichier source CSV.', 'Données Incomplètes', 5),
(6, 'Latence anormale sur l''API de traduction.', 'Latence Réseau', 6),
(7, 'Vibration anormale du support de test.', 'Problème Mécanique', 7),
(8, 'Colmatage partiel du filtre du réacteur.', 'Obstruction Réactif', 8),
(9, 'Erreur de syntaxe dans le script de calcul.', 'Erreur Script', 9),
(10, 'Incohérence détectée dans le modèle économétrique.', 'Erreur Modèle', 10);

INSERT INTO DOCUMENT (id_doc, nom_doc, type_doc, id_exp) VALUES
(1, 'Protocole_ML_V1.pdf', 'protocole expérimental', 1),
(2, 'Questionnaire_Enquete.pdf', 'questionnaires', 2),
(3, 'Consentement_Participants.pdf', 'fiches de consentement', 3),
(4, 'Rapport_Administratif_Q1.docx', 'autres documents administratifs', 4),
(5, 'Protocole_Chimie_Vert.pdf', 'protocole expérimental', 5),
(6, 'Questionnaire_Evaluation.pdf', 'questionnaires', 6),
(7, 'Consentement_Test_Acoustique.pdf', 'fiches de consentement', 7),
(8, 'Note_Administrative_Projet.pdf', 'autres documents administratifs', 8),
(9, 'Protocole_EDP.pdf', 'protocole expérimental', 9),
(10, 'Rapport_Technique_Final.pdf', 'rapport technique', 10);

INSERT INTO TRAVAILLER (id_exp, id_util, num_ordre) VALUES
(1, 3, '1'),
(1, 7, '2'),
(2, 4, '1'),
(3, 5, '1'),
(4, 6, '2'),
(5, 3, '2'),
(6, 8, '1'),
(7, 4, '2'),
(8, 5, '2'),
(9, 6, '1');

INSERT INTO CONTENIR (id_sess, id_ress, num_attribution) VALUES
(1, 3, '1'),
(2, 4, '1'),
(3, 1, '1'),
(4, 7, '1'),
(5, 5, '1'),
(6, 3, '2'),
(7, 10, '1'),
(8, 2, '1'),
(9, 6, '1'),
(10, 9, '1');

INSERT INTO ECHANGER (id_util_receveur, id_util_expéditeur, date_envoi, id_mess, contenu_mess) VALUES
(7, 3, '2024-01-10 10:00:00', 1, 'Veuillez trouver ci-joint le protocole validé pour les tests.'),
(3, 7, '2024-01-10 11:15:00', 2, 'Document bien reçu, nous procédons aux acquisitions.'),
(8, 4, '2024-03-01 09:30:00', 3, 'Le point d’étape est planifié pour la semaine prochaine.'),
(4, 8, '2024-03-01 10:00:00', 4, 'Participation confirmée de notre côté.'),
(9, 5, '2024-09-02 14:20:00', 5, 'La mise à jour du modèle est déployée sur le serveur.'),
(5, 9, '2024-09-02 15:00:00', 6, 'Validation des résultats en cours d’analyse.'),
(10, 6, '2024-02-01 08:45:00', 7, 'Merci de transférer les derniers jeux de données.'),
(6, 10, '2024-06-15 16:30:00', 8, 'Transfert effectué via la plateforme sécurisée.'),
(8, 3, '2025-01-10 09:00:00', 9, 'Réunion technique reportée à vendredi.'),
(7, 5, '2025-01-10 09:30:00', 10, 'Rapport intermédiaire disponible en téléchargement.'),
(11, 3, '2020-10-10 09:00:00', 11, 'Test 1'),
(11, 5, '2020-10-10 10:00:00', 12, 'Contrary to popular belief, Lorem Ipsum is not simply random text. 
It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. 
Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the 
more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the 
word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 
and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. 
This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem 
Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32. The standard chunk of Lorem Ipsum 
used since 1966 is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum 
et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from 
the 1914 translation by H. Rackham.');

INSERT INTO REFERENCER (id_proj, id_util, id_role, nom_role) VALUES
(1, 3, 1, 'responsable'),
(1, 7, 2, 'participant'),
(2, 4, 3, 'responsable'),
(2, 8, 4, 'participant'),
(3, 5, 5, 'responsable'),
(3, 9, 6, 'participant'),
(4, 6, 7, 'responsable'),
(4, 10, 8, 'participant'),
(5, 3, 9, 'gestionnaire'),
(6, 4, 10, 'membre');

INSERT INTO RAPPORT (id_rap, titre_rap, nbparticipant_rap, nbincid_rap, desc_rap, date_rap, id_exp) VALUES
(1, 'Rapport intermédiaire - Benchmark Transformers', 1, 1, 'Rapport intermédiaire : premiers tests de benchmark validés malgré une surchauffe GPU.', '2024-02-15', 1),
(2, 'Bilan mi-parcours - Simulation Monte-Carlo', 1, 1, 'Bilan à mi-parcours de la simulation Monte-Carlo au LMM.', '2024-10-15', 4);

INSERT INTO RESERVATION (datedeb_res, datefin_res, id_sess, id_ress) VALUES
-- Session 1 (2024-01-15) : Microscope Électronique (1) + Salle d'Expérimentation 101 (14)
('2024-01-15 08:30:00', '2024-01-15 17:30:00', 1, 1),
('2024-01-15 09:00:00', '2024-01-15 12:00:00', 1, 14),

-- Session 2 (2023-05-20) : Spectromètre de Masse (2)
('2023-05-20 09:00:00', '2023-05-20 16:00:00', 2, 2),

-- Session 3 (2024-03-02) : Supercalculateur Nœud A (3) + Station de Calcul GPU A (17)
('2024-03-02 10:00:00', '2024-03-02 18:00:00', 3, 3),
('2024-03-02 10:00:00', '2024-03-02 14:00:00', 3, 17),

-- Session 4 (2024-09-05, En cours) : Casque Réalité Virtuelle Pro (26) + Salle d'Observation 102 (15)
('2024-09-05 14:00:00', '2024-09-05 18:00:00', 4, 26),
('2024-09-05 14:30:00', '2024-09-05 17:30:00', 4, 15),

-- Session 5 (2024-01-12) : Balance de Précision (23) + Centrifugeuse de Paillasse (24)
('2024-01-12 08:00:00', '2024-01-12 12:00:00', 5, 23),
('2024-01-12 09:30:00', '2024-01-12 11:30:00', 5, 24),

-- Session 6 (2024-03-20, En cours) : Oscilloscope Numérique (4) + Générateur de Signaux (20)
('2024-03-20 09:30:00', '2024-03-20 12:30:00', 6, 4),
('2024-03-20 13:30:00', '2024-03-20 17:00:00', 6, 20),

-- Session 7 (2023-02-10) : Cuve Acoustique Immergée (10) + Capteur de Pression Subaquatique (30)
('2023-02-10 08:00:00', '2023-02-10 14:00:00', 7, 10),
('2023-02-10 10:00:00', '2023-02-10 16:00:00', 7, 30),

-- Session 8 (2024-07-05) : Eye-Tracker Fixe (27) + Table Tactile Interactive (28)
('2024-07-05 13:00:00', '2024-07-05 17:00:00', 8, 27),
('2024-07-05 13:30:00', '2024-07-05 16:30:00', 8, 28),

-- Session 9 (2024-03-18) : Salle de Réunion A101 (5) + Videoprojecteur Mobile 4K (31)
('2024-03-18 09:00:00', '2024-03-18 12:00:00', 9, 5),
('2024-03-18 09:00:00', '2024-03-18 12:00:00', 9, 31),

-- Session 10 (2025-02-05, Planifié) : Amphithéâtre Euler (6) + Kit Micros & Enceintes Conférence (32)
('2025-02-05 08:30:00', '2025-02-05 12:30:00', 10, 6),
('2025-02-05 08:30:00', '2025-02-05 12:30:00', 10, 32);
