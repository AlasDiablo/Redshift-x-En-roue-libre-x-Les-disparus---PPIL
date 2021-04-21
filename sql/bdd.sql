DROP TABLE IF EXISTS ville_intermediaire;
DROP TABLE IF EXISTS passager;
DROP TABLE IF EXISTS trajet;
DROP TABLE IF EXISTS membre;
DROP TABLE IF EXISTS groupe;
DROP TABLE IF EXISTS notification;
DROP TABLE IF EXISTS utilisateur;

CREATE TABLE IF NOT EXISTS utilisateur (
    email varchar(255) NOT NULL,
    mdp varchar(255) NOT NULL,
    nom varchar(25) NOT NULL,
    prenom varchar(25) NOT NULL,
    tel integer NOT NULL,
    sexe varchar(1) NOT NULL,
    a_voiture varchar(1) NOT NULL,
    url_img varchar(255) DEFAULT NULL,
    note decimal(2,1) DEFAULT NULL,
    activer_notif varchar(1) DEFAULT 'N',
    PRIMARY KEY (email),
    CONSTRAINT check_sexe_utilisateur CHECK (sexe IN ('H','F')),
    CONSTRAINT check_voiture_utilisateur CHECK (a_voiture IN ('O','N')),
    CONSTRAINT check_notif_utilisateur CHECK (activer_notif IN ('O','N'))
);

create table if not exists mark (
    mark_from varchar(255),
    mark_for varchar(255),
    mark integer,
    primary key (mark_for, mark_from),
    constraint fk_mark_from foreign key (mark_from) references utilisateur(email),
    constraint fk_mark_for foreign key (mark_for) references utilisateur(email)
);

CREATE TABLE IF NOT EXISTS notification (
    id_notif integer NOT NULL,
    utilisateur varchar(255) NOT NULL,
    emeteur varchar(255) NOT NULL,
    message text NOT NULL,
    vu varchar(1) DEFAULT 'N',
    PRIMARY KEY (id_notif),
    CONSTRAINT fk_utilisateur_notif FOREIGN KEY (utilisateur) REFERENCES utilisateur(email) ON DELETE CASCADE,
    CONSTRAINT fk_emeteur_notif FOREIGN KEY (emeteur) REFERENCES utilisateur(email) ON DELETE CASCADE,
    CONSTRAINT check_vu_notif CHECK (vu IN ('O','N'))
);

CREATE TABLE IF NOT EXISTS groupe (
    id_groupe integer NOT NULL,
    nom varchar(25) NOT NULL,
    email_createur varchar(255) NOT NULL,
    url_img varchar(255) DEFAULT NULL,
    PRIMARY KEY (id_groupe),
    CONSTRAINT fk_mail_createur_groupe FOREIGN KEY (email_createur) REFERENCES utilisateur(email) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS membre (
    email_membre varchar(255) NOT NULL,
    id_groupe integer NOT NULL,
    reponse varchar(1) DEFAULT 'N',
    PRIMARY KEY (email_membre,id_groupe),
    CONSTRAINT fk_mail_membre_membre FOREIGN KEY (email_membre) REFERENCES utilisateur(email) ON DELETE CASCADE,
    CONSTRAINT fk_id_groupe_membre FOREIGN KEY (id_groupe) REFERENCES groupe(id_groupe) ON DELETE CASCADE,
    CONSTRAINT check_reponse_membre CHECK (reponse IN ('O','N'))
);

CREATE TABLE IF NOT EXISTS trajet (
    id_trajet integer NOT NULL,
    date date NOT NULL,
    ville_depart varchar(255) NOT NULL,
    ville_arrivee varchar(255) NOT NULL,
    heure_depart time NOT NULL,
    email_conducteur varchar(255) NOT NULL,
    nbr_passager integer NOT NULL DEFAULT 1,
    id_groupe integer DEFAULT NULL,
    prix integer NOT NULL,
    lieuxRDV varchar(512),
    commentaires varchar(1024),
    PRIMARY KEY (id_trajet),
    CONSTRAINT fk_mail_conducteur_trajet FOREIGN KEY (email_conducteur) REFERENCES utilisateur(email) ON DELETE CASCADE,
    CONSTRAINT fk_id_groupe_trajet FOREIGN KEY (id_groupe) REFERENCES groupe(id_groupe) ON DELETE CASCADE,
    CONSTRAINT check_nb_passagers_trajet CHECK (nbr_passager >= 0),
    CONSTRAINT check_prix_trajet CHECK (prix >= 0)
);

CREATE TABLE IF NOT EXISTS ville_intermediaire (
    id_trajet integer NOT NULL,
    ville varchar(255) NOT NULL,
    CONSTRAINT fk_id_trajet_ville_intermediaire FOREIGN KEY (id_trajet) REFERENCES trajet(id_trajet) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS passager (
    email_passager varchar(255) NOT NULL,
    id_trajet integer NOT NULL,
    reponse varchar(1) DEFAULT 'N',
    PRIMARY KEY (email_passager,id_trajet),
    CONSTRAINT fk_mail_passager FOREIGN KEY (email_passager) REFERENCES utilisateur(email) ON DELETE CASCADE,
    CONSTRAINT fk_id_trajet_passager FOREIGN KEY (id_trajet) REFERENCES trajet(id_trajet) ON DELETE CASCADE,
    CONSTRAINT check_reponse_passager CHECK (reponse IN ('O','N'))
);

CREATE TABLE IF NOT EXISTS forgotten_password
(
    email     VARCHAR(255) NOT NULL,
    reset_key VARCHAR(255) NOT NULL,
    PRIMARY KEY (email, reset_key),
    CONSTRAINT fk_mail_rest FOREIGN KEY (email) REFERENCES utilisateur (email)
);
