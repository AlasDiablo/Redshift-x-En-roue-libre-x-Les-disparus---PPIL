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
    `tel` integer NOT NULL,
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
    `id_notif` integer NOT NULL,
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
    `id_groupe` integer NOT NULL,
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
    `id_trajet` integer NOT NULL,
    `date` date NOT NULL,
    `ville_depart` varchar(255) NOT NULL,
    `ville_arrivee` varchar(255) NOT NULL,
    `heure_depart` time NOT NULL,
    `email_conducteur` varchar(255) NOT NULL,
    `nbr_passager` integer NOT NULL DEFAULT 1,
    `id_groupe` integer DEFAULT NULL,
    `prix` integer NOT NULL,
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
