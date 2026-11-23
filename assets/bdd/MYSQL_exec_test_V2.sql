USE Lab_Project_GRP04;


/* ------Enseignant chercheur------ */

CALL sp_AjouterSessionV2('2026-11-01 09:00:00', '2026-11-01 12:00:00', 'Planifié', 1);

CALL sp_AjouterRessourceASessionV2(1, 5, 1);

CALL sp_AjouterDocument('Rapport_Experimentation_1.pdf', 'PDF', 1);


/* ------Gestionnaire------ */

CALL sp_AjouterRessourceLaboratoireV2('Imprimante 3D', 'Matériel de prototypage rapide pour les projets');

CALL sp_SupprimerRessourceLaboratoireV2(11);

CALL sp_ModifierRessourceV2(1, 'Salle de Réunion A101 (Rénovée)', 'Salle de réunion équipée avec écran interactif tactile');


/* ------Participant------ */

CALL sp_MettreAJourProfilParticipantV2(2, '1995-04-12', 'M', 'Français, Anglais', 'Aucune contrainte particulière');

CALL sp_EnvoyerMessageParticipantV2(7, 3, 'Bonjour, je confirme ma présence pour la prochaine session.');

CALL sp_InscrireParticipantExperimentationV2(4, 1, 1);