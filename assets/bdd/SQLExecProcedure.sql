USE Lab_Project_GRP04;
GO


/*  PROCÉDURES ENSEIGNANT-CHERCHEUR         */


-- Ajouter une session
EXEC sp_AjouterSessionV2 
    @p_datedeb_sess = '2026-11-01 09:00:00', 
    @p_datefin_sess = '2026-11-01 12:00:00', 
    @p_statut_sess = 'Planifié', 
    @p_id_exp = 1;
GO

-- Associer une ressource à une session
EXEC sp_AjouterRessourceASessionV2 
    @p_id_sess = 1, 
    @p_id_ress = 5, 
    @p_num_attribution = 1;
GO

-- Ajouter un document à une expérimentation
EXEC sp_AjouterDocument 
    @p_nom_doc = 'Rapport_Experimentation_1.pdf', 
    @p_type_doc = 'PDF', 
    @p_id_exp = 1;
GO



/*  PROCÉDURES GESTIONNAIRE                 */


-- Ajouter une ressource au laboratoire
EXEC sp_AjouterRessourceLaboratoireV2 
    @p_nom_ress = 'Imprimante 3D', 
    @p_desc_ress = 'Matériel de prototypage rapide pour les projets';
GO

-- Supprimer une ressource du laboratoire
EXEC sp_SupprimerRessourceLaboratoireV2 
    @p_id_ress = 11;
GO



-- Modifier une ressource du laboratoire
EXEC sp_ModifierRessourceV2 
    @p_id_ress = 1, 
    @p_nom_ress = 'Salle de Réunion A101 (Rénovée)', 
    @p_desc_ress = 'Salle de réunion équipée avec écran interactif tactile';
GO



/*  PROCÉDURES PARTICIPANT                  */


-- Mettre à jour le profil utilisateur 
EXEC sp_MettreAJourProfilParticipantV2 
    @p_id_util = 2, 
    @p_date_util = '1995-04-12', 
    @p_genre_util = 'M', 
    @p_langues_util = 'Français, Anglais', 
    @p_contr_util = 'Aucune contrainte particulière';
GO

-- Envoyer un message 
EXEC sp_EnvoyerMessageParticipantV2 
    @p_id_util_expediteur = 7, 
    @p_id_util_receveur = 3, 
    @p_contenu_mess = 'Bonjour, je confirme ma présence pour la prochaine session.';
GO

-- S'inscrire à une expérimentation 
EXEC sp_InscrireParticipantExperimentationV2 
    @p_id_util = 4, 
    @p_id_exp = 1, 
    @p_num_ordre = 1;
GO