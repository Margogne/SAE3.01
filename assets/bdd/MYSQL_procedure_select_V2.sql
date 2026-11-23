USE Lab_Project_GRP04;


/* ------Enseignant chercheur------ */

SELECT nom_doc FROM PROJET AS proj
JOIN EXPERIMENTATION as exp ON exp.id_proj=proj.id_proj 
JOIN DOCUMENT AS d ON d.id_exp=exp.id_exp
JOIN REFERENCER AS ref ON ref.id_proj=proj.id_proj
WHERE ref.id_util=4;

SELECT contenu_mess, date_envoi 
FROM ECHANGER
WHERE (id_util_expéditeur = 4 AND id_util_receveur = 2)
   OR (id_util_expéditeur = 2 AND id_util_receveur = 4);

SELECT id_incid, titre_incid, desc_incid FROM PROJET as proj
JOIN EXPERIMENTATION as exp ON exp.id_proj=proj.id_proj
JOIN SESSION AS sess ON sess.id_exp=exp.id_exp
JOIN INCIDENT AS incid ON incid.id_sess=sess.id_sess
WHERE sess.datefin_sess>'2026-09-01' AND proj.id_proj=8;

DELIMITER $$
DROP PROCEDURE IF EXISTS sp_AjouterSessionV2$$
CREATE PROCEDURE sp_AjouterSessionV2(
    IN p_datedeb_sess DATETIME,
    IN p_datefin_sess DATETIME,
    IN p_statut_sess VARCHAR(50),
    IN p_id_exp INT
)
BEGIN
    IF p_datedeb_sess >= p_datefin_sess THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La date de début de session doit être inférieure à la date de fin.';
    END IF;
    
	IF NOT EXISTS(SELECT * FROM EXPERIMENTATION WHERE id_exp = p_id_exp) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cette expérimentation n existe pas';
    END IF;
    
    INSERT INTO SESSION (datedeb_sess, datefin_sess, statut_sess, id_exp)
    VALUES (p_datedeb_sess, p_datefin_sess, p_statut_sess, p_id_exp);

    SELECT 'Session ajoutée avec succès !' AS Message;
END $$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS sp_AjouterRessourceASessionV2$$
CREATE PROCEDURE sp_AjouterRessourceASessionV2(
    IN p_id_sess INT,
    IN p_id_ress INT,
    IN p_num_attribution INT
)
BEGIN
    DECLARE v_datedeb_sess DATETIME;
    DECLARE v_datefin_sess DATETIME;

    IF NOT EXISTS (SELECT * FROM SESSION WHERE id_sess = p_id_sess) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La session spécifiée n''existe pas.';
    END IF;

    SELECT datedeb_sess, datefin_sess INTO v_datedeb_sess, v_datefin_sess FROM SESSION WHERE id_sess = p_id_sess;

    IF NOT EXISTS (SELECT 1 FROM RESSOURCE WHERE id_ress = p_id_ress) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La ressource spécifiée n''existe pas.';
    END IF;

    IF EXISTS (SELECT 1 FROM CONTENIR c 
				INNER JOIN SESSION s ON c.id_sess = s.id_sess
				WHERE c.id_ress = p_id_ress AND s.datedeb_sess < v_datefin_sess AND s.datefin_sess > v_datedeb_sess
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La ressource est déjà réservée sur un créneau horaire incompatible.';
    END IF;

    INSERT INTO CONTENIR (id_sess, id_ress, num_attribution) VALUES (p_id_sess, p_id_ress, p_num_attribution);
    SELECT 'Ressource réservée et affectée à la session avec succès !' AS Message;
END $$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS sp_AjouterDocument$$
CREATE PROCEDURE sp_AjouterDocument(
    IN p_nom_doc VARCHAR(255),
    IN p_type_doc VARCHAR(50),
    IN p_id_exp INT
)
BEGIN
    IF NOT EXISTS (SELECT 1 FROM EXPERIMENTATION WHERE id_exp = p_id_exp) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'L''expérimentation spécifiée n''existe pas.';
    END IF;

    INSERT INTO DOCUMENT (nom_doc, type_doc, id_exp)
    VALUES (p_nom_doc, p_type_doc, p_id_exp);

    SELECT 'Document ajouté avec succès !' AS Message;
END $$
DELIMITER ;


/* ------Gestionnaire------ */

SELECT res.id_ress, nom_ress, desc_ress FROM PROJET AS proj
JOIN EXPERIMENTATION AS exp ON exp.id_proj=proj.id_proj
JOIN SESSION AS sess ON sess.id_exp=exp.id_exp
JOIN CONTENIR AS cont ON cont.id_sess=sess.id_sess
JOIN RESSOURCE AS res ON res.id_ress=cont.id_ress
WHERE proj.id_proj=3;

SELECT res.id_ress, nom_ress, desc_ress FROM RESSOURCE as res
WHERE res.id_ress NOT IN (SELECT id_ress FROM CONTENIR);

SELECT 
    r.id_ress,
    r.nom_ress,
    s.id_sess,
    s.datedeb_sess,
    s.datefin_sess,
    s.statut_sess,
    e.titre_exp
FROM RESSOURCE r
JOIN CONTENIR c ON r.id_ress = c.id_ress
JOIN SESSION s ON c.id_sess = s.id_sess
JOIN EXPERIMENTATION e ON s.id_exp = e.id_exp
WHERE r.id_ress = 5 
ORDER BY s.datedeb_sess ASC;

DELIMITER $$
DROP PROCEDURE IF EXISTS sp_SupprimerRessourceLaboratoireV2$$
CREATE PROCEDURE sp_SupprimerRessourceLaboratoireV2(
    IN p_id_ress INT
)
BEGIN
    IF NOT EXISTS (SELECT 1 FROM RESSOURCE WHERE id_ress = p_id_ress) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La ressource spécifiée n''existe pas.';
    END IF;
    
    IF EXISTS (SELECT 1 FROM CONTENIR WHERE id_ress = p_id_ress) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de supprimer cette ressource car elle est associée à une ou plusieurs sessions (historique ou réservations en cours).';
    END IF;

    DELETE FROM RESSOURCE WHERE id_ress = p_id_ress;

    SELECT 'La ressource a été supprimée avec succès du laboratoire.' AS Message;
END $$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS sp_AjouterRessourceLaboratoireV2$$
CREATE PROCEDURE sp_AjouterRessourceLaboratoireV2(
    IN p_nom_ress VARCHAR(255),
    IN p_desc_ress TEXT
)
BEGIN
    INSERT INTO RESSOURCE (nom_ress, desc_ress) VALUES (p_nom_ress, p_desc_ress);
    SELECT 'Nouvelle ressource enregistrée avec succès dans le système.' AS Message;
END $$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS sp_ModifierRessourceV2$$
CREATE PROCEDURE sp_ModifierRessourceV2(
    IN p_id_ress INT,
    IN p_nom_ress VARCHAR(255),
    IN p_desc_ress TEXT
)
BEGIN
    IF NOT EXISTS (SELECT 1 FROM RESSOURCE WHERE id_ress = p_id_ress) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La ressource spécifiée n''existe pas.';
    END IF;

    UPDATE RESSOURCE SET nom_ress = p_nom_ress, desc_ress = p_desc_ress WHERE id_ress = p_id_ress;

    SELECT 'Les informations de la ressource ont été mises à jour avec succès.' AS Message;
END $$
DELIMITER ;


/* ------Participant------ */

SELECT 
    e.id_exp,
    e.titre_exp,
    e.desc_exp,
    e.datedeb_exp,
    e.datefin_exp,
    e.retrib_exp,
    p.nom_proj
FROM EXPERIMENTATION e
JOIN PROJET p ON e.id_proj = p.id_proj
WHERE e.datefin_exp >= NOW() 
ORDER BY e.datedeb_exp ASC;

SELECT 
    e.id_exp,
    e.titre_exp,
    e.desc_exp,
    e.datedeb_exp,
    e.datefin_exp,
    t.num_ordre
FROM TRAVAILLER t
JOIN EXPERIMENTATION e ON t.id_exp = e.id_exp
WHERE t.id_util = 10
ORDER BY e.datedeb_exp DESC;

SELECT 
    m.date_envoi,
    expediteur.nom_util AS nom_expediteur,
    expediteur.prenom_util AS prenom_expediteur,
    receveur.nom_util AS nom_receveur,
    receveur.prenom_util AS prenom_receveur,
    m.contenu_mess
FROM ECHANGER m
JOIN UTILISATEUR expediteur ON m.id_util_expéditeur = expediteur.id_util
JOIN UTILISATEUR receveur ON m.id_util_receveur = receveur.id_util
WHERE m.id_util_expéditeur = 10 OR m.id_util_receveur = 10
ORDER BY m.date_envoi DESC;

DELIMITER $$
DROP PROCEDURE IF EXISTS sp_MettreAJourProfilParticipantV2$$
CREATE PROCEDURE sp_MettreAJourProfilParticipantV2(
    IN p_id_util INT,
    IN p_date_util DATE,
    IN p_genre_util VARCHAR(50),
    IN p_langues_util VARCHAR(255),
    IN p_contr_util VARCHAR(255)
)
BEGIN
    IF NOT EXISTS (SELECT 1 FROM UTILISATEUR WHERE id_util = p_id_util) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Utilisateur introuvable.';
    END IF;

    UPDATE UTILISATEUR SET date_util = p_date_util, genre_util = p_genre_util, langues_util = p_langues_util, contr_util = p_contr_util 
	WHERE id_util = p_id_util;

    SELECT 'Profil mis à jour avec succès.' AS Message;
END $$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS sp_EnvoyerMessageParticipantV2$$
CREATE PROCEDURE sp_EnvoyerMessageParticipantV2(
    IN p_id_util_expediteur INT,
    IN p_id_util_receveur INT,
    IN p_contenu_mess TEXT
)
BEGIN
    IF NOT EXISTS (SELECT 1 FROM UTILISATEUR WHERE id_util = p_id_util_expediteur) 
       OR NOT EXISTS (SELECT 1 FROM UTILISATEUR WHERE id_util = p_id_util_receveur) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'L''expéditeur ou le destinataire est invalide.';
    END IF;

    INSERT INTO ECHANGER (id_util_expéditeur, id_util_receveur, date_envoi, contenu_mess)
    VALUES (p_id_util_expediteur, p_id_util_receveur, NOW(), p_contenu_mess);

    SELECT 'Message envoyé avec succès au responsable.' AS Message;
END $$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS sp_InscrireParticipantExperimentationV2$$
CREATE PROCEDURE sp_InscrireParticipantExperimentationV2(
    IN p_id_util INT,
    IN p_id_exp INT,
    IN p_num_ordre INT
)
BEGIN
    IF NOT EXISTS (SELECT 1 FROM UTILISATEUR WHERE id_util = p_id_util) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Utilisateur introuvable.';
    END IF;
    
    IF NOT EXISTS (SELECT 1 FROM EXPERIMENTATION WHERE id_exp = p_id_exp) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'L''expérimentation spécifiée n''existe pas.';
    END IF;

    IF EXISTS (SELECT 1 FROM TRAVAILLER WHERE id_util = p_id_util AND id_exp = p_id_exp) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Vous êtes déjà inscrit à cette expérimentation.';
    END IF;

    INSERT INTO TRAVAILLER (id_exp, id_util, num_ordre)
    VALUES (p_id_exp, p_id_util, p_num_ordre);

    SELECT 'Inscription à l''expérimentation effectuée avec succès !' AS Message;
END $$
DELIMITER ;