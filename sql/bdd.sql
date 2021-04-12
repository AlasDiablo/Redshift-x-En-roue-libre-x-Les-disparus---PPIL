DROP TABLE IF EXISTS `Ville_intermediaire`;
DROP TABLE IF EXISTS `Passager`;
DROP TABLE IF EXISTS `Trajet`;
DROP TABLE IF EXISTS `Membre`;
DROP TABLE IF EXISTS `Groupe`;
DROP TABLE IF EXISTS `Notification`;
DROP TABLE IF EXISTS `Utilisateur`;


CREATE TABLE IF NOT EXISTS `Utilisateur` (
    `email` varchar(255) NOT NULL,
    `mdp` varchar(255) NOT NULL,
    `nom` varchar(25) NOT NULL,
    `prenom` varchar(25) NOT NULL,
    `tel` varchar(10) NOT NULL,
    `sexe` varchar(1) NOT NULL,
    `a_voiture` varchar(1) NOT NULL,
    `url_img` varchar(255) DEFAULT NULL,
    `note` decimal(2,1) DEFAULT NULL,
    `activer_notif` varchar(1) DEFAULT 'N',
    PRIMARY KEY (`email`),
    CONSTRAINT check_sexe_utilisateur CHECK (`sexe` IN ('H','F')),
    CONSTRAINT check_voiture_utilisateur CHECK (`a_voiture` IN ('O','N')),
    CONSTRAINT check_notif_utilisateur CHECK (`activer_notif` IN ('O','N'))
);

CREATE TABLE IF NOT EXISTS `Notification` (
    `id_notif` integer NOT NULL AUTO_INCREMENT,
    `utilisateur` varchar(255) NOT NULL,
    `emeteur` varchar(255) NOT NULL,
    `message` varchar(255) NOT NULL,
    `vu` varchar(1) DEFAULT 'N',
    PRIMARY KEY (`id_notif`),
    CONSTRAINT fk_utilisateur_notif FOREIGN KEY (`utilisateur`) REFERENCES `Utilisateur`(`email`) ON DELETE CASCADE,
    CONSTRAINT fk_emeteur_notif FOREIGN KEY (`emeteur`) REFERENCES `Utilisateur`(`email`) ON DELETE CASCADE,
    CONSTRAINT check_vu_notif CHECK (`vu` IN ('O','N'))
);

CREATE TABLE IF NOT EXISTS `Groupe` (
    `id_groupe` integer NOT NULL AUTO_INCREMENT,
    `nom` varchar(25) NOT NULL,
    `email_createur` varchar(255) NOT NULL,
    `url_img` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id_groupe`),
    CONSTRAINT fk_mail_createur_groupe FOREIGN KEY (`email_createur`) REFERENCES `Utilisateur`(`email`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `Membre` (
    `email_membre` varchar(255) NOT NULL,
    `id_groupe` integer NOT NULL,
    `reponse` varchar(1) DEFAULT 'N',
    PRIMARY KEY (`email_membre`,`id_groupe`),
    CONSTRAINT fk_mail_membre_membre FOREIGN KEY (`email_membre`) REFERENCES `Utilisateur`(`email`) ON DELETE CASCADE,
    CONSTRAINT fk_id_groupe_membre FOREIGN KEY (`id_groupe`) REFERENCES `Groupe`(`id_groupe`) ON DELETE CASCADE,
    CONSTRAINT check_reponse_membre CHECK (`reponse` IN ('O','N'))
);

CREATE TABLE IF NOT EXISTS `Trajet` (
    `id_trajet` integer NOT NULL AUTO_INCREMENT,
    `date` date NOT NULL,
    `ville_depart` varchar(255) NOT NULL,
    `ville_arrivee` varchar(255) NOT NULL,
    `heure_depart` time NOT NULL,
    `email_conducteur` varchar(255) NOT NULL,
    `nbr_passager` integer NOT NULL DEFAULT 1,
    `nb_max_passager` integer NOT NULL DEFAULT 2,
    `id_groupe` integer DEFAULT NULL,
    `prix` integer NOT NULL,
    `lieuxRDV` varchar(512),
    `commentaires` varchar(1024),
    PRIMARY KEY (`id_trajet`),
    CONSTRAINT fk_mail_conducteur_trajet FOREIGN KEY (`email_conducteur`) REFERENCES `Utilisateur`(`email`) ON DELETE CASCADE,
    CONSTRAINT fk_id_groupe_trajet FOREIGN KEY (`id_groupe`) REFERENCES `Groupe`(`id_groupe`) ON DELETE CASCADE,
    CONSTRAINT check_nb_passagers_trajet CHECK (`nbr_passager` >= 0),
    CONSTRAINT check_prix_trajet CHECK (`prix` >= 0)
);

CREATE TABLE IF NOT EXISTS `Ville_intermediaire` (
    `id_trajet` integer NOT NULL,
    `ville` varchar(255) NOT NULL,
    CONSTRAINT fk_id_trajet_ville_intermediaire FOREIGN KEY (`id_trajet`) REFERENCES `Trajet`(`id_trajet`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `Passager` (
    `email_passager` varchar(255) NOT NULL,
    `id_trajet` integer NOT NULL,
    `reponse` varchar(1) DEFAULT 'N',
    PRIMARY KEY (`email_passager`,`id_trajet`),
    CONSTRAINT fk_mail_passager FOREIGN KEY (`email_passager`) REFERENCES `Utilisateur`(`email`) ON DELETE CASCADE,
    CONSTRAINT fk_id_trajet_passager FOREIGN KEY (`id_trajet`) REFERENCES `Trajet`(`id_trajet`) ON DELETE CASCADE,
    CONSTRAINT check_reponse_passager CHECK (`reponse` IN ('O','N'))
);

CREATE TABLE IF NOT EXISTS forgotten_password
(
    email     VARCHAR(255) NOT NULL,
    reset_key VARCHAR(255) NOT NULL,
    PRIMARY KEY (email, reset_key),
    CONSTRAINT fk_mail_rest FOREIGN KEY (email) REFERENCES Utilisateur (email)
);


DELIMITER |
CREATE OR REPLACE TRIGGER check_date
    BEFORE INSERT ON `Trajet` FOR EACH ROW
    BEGIN
        IF (NEW.`date`<CURRENT_DATE) THEN
            SIGNAL SQLSTATE '20300' SET MESSAGE_TEXT = 'Date rentree ulterieur a date actuelle.';
        END IF;
    END;
    |

DELIMITER |
CREATE OR REPLACE TRIGGER add_passager
    BEFORE UPDATE ON `Passager` FOR EACH ROW
    BEGIN
	DECLARE x integer;
    	DECLARE y integer;
	DECLARE z integer;
	SELECT `nbr_passager`, `nb_max_passager`, `id_trajet` INTO x, y, z FROM `Trajet` LEFT JOIN `Passager` ON `Trajet`.`id_trajet`=`Passager`.`id_trajet`;
        IF (NEW.`reponse`!="N" AND x<y) THEN
            UPDATE `Trajet` SET `nbr_passager`=`nbr_passager`+1 WHERE `id_trajet`=z;
        END IF;
    END;
    |

DELIMITER |
CREATE OR REPLACE TRIGGER rem_passager
    BEFORE UPDATE ON `Passager` FOR EACH ROW
    BEGIN
	DECLARE z integer;
	SELECT `id_trajet` INTO z FROM `Passager` ;
        IF (NEW.`reponse`="N") THEN
            UPDATE `Trajet` SET `nbr_passager`=`nbr_passager`-1 WHERE `id_trajet`=z;
        END IF;
    END;
    |

DELIMITER |
CREATE OR REPLACE PROCEDURE `insert_groupe` (IN u VARCHAR(25), IN e VARCHAR(255), IN n VARCHAR(255))  BEGIN

INSERT INTO `Groupe` (`nom`, `email_createur`, `url_img`) VALUES (i,u,e,n);

END;
|

DELIMITER |
CREATE OR REPLACE PROCEDURE `insert_vi` (IN id INT, IN u VARCHAR(255))  BEGIN

INSERT INTO `Ville_intermediaire`(`id_trajet`, `ville`) VALUES (id, u);

END;
|

DELIMITER |
CREATE OR REPLACE PROCEDURE `insert_notif` (IN u VARCHAR(255), IN e VARCHAR(255), IN m VARCHAR(255), IN v VARCHAR(1))  BEGIN

INSERT INTO `Notification`(`utilisateur`, `emeteur`, `message`, `vu`) VALUES (id, u, e, m, v);

END;
|

DELIMITER |
CREATE OR REPLACE PROCEDURE `insert_passager` (IN `u` VARCHAR(255), IN `id` INT, IN `v` VARCHAR(1))  BEGIN

INSERT INTO `Passager`(`email_passager`, `id_trajet`,`reponse`) VALUES (u, id, v);

END;
|

DELIMITER |
CREATE OR REPLACE PROCEDURE `insert_trajet` (IN `d` DATE, IN `e` VARCHAR(255), IN `m` VARCHAR(255), IN `hd` TIME, IN `ed` VARCHAR(255), IN `v` INT, IN `nbm` INT, IN `g` INT, IN `p` INT, IN `rdv` VARCHAR(512),  IN `commentaire` VARCHAR(1024))  BEGIN

INSERT INTO `Trajet`(`date`, `ville_depart`, `ville_arrivee`, `heure_depart`, `email_conducteur`, `nbr_passager`, `nb_max_passager`, `id_groupe`, `prix`, `lieuRDV`, `commentaires`) VALUES (id, d, e, m, hd, ed, v, nbm, g, p, rdv, commentaire);

END;
|

DELIMITER |
CREATE OR REPLACE PROCEDURE `insert_user` (IN `e` VARCHAR(255), IN `m` VARCHAR(255), IN `n` VARCHAR(25), IN `p` VARCHAR(25), IN `t` VARCHAR(25), IN `s` VARCHAR(1), IN `v` VARCHAR(1), IN `i` VARCHAR(255), IN `no` DECIMAL(2,1), IN `notif` VARCHAR(1))  BEGIN

INSERT INTO `Utilisateur` (`email`, `mdp`, `nom`, `prenom`, `tel`, `sexe`, `a_voiture`, `url_img`, `note`, `activer_notif`) VALUES (e, m, n, p, t, s, v, i, no, notif);

END;
|

DELIMITER |
CREATE OR REPLACE PROCEDURE insert_membre(IN u VARCHAR(255), IN i INT, IN v VARCHAR(1))
BEGIN

INSERT INTO `Membre`(`email_membre`, `id_groupe`, `reponse`) VALUES (u,i,v);

END;
|
