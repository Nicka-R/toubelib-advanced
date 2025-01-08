CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

DROP TABLE IF EXISTS "specialite";

CREATE TABLE specialite (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    label VARCHAR(100),
    description TEXT
);

DROP TABLE IF EXISTS "praticien";

CREATE TABLE praticien (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    nom VARCHAR(100),
    prenom VARCHAR(100),
    adresse VARCHAR(255),
    telephone VARCHAR(20),
    specialite_id UUID,
    FOREIGN KEY (specialite_id) REFERENCES specialite(id)
);

DROP TABLE IF EXISTS "patientAdmin";

CREATE TABLE patientAdmin (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    nom VARCHAR(100),
    prenom VARCHAR(100),
    nss VARCHAR(15),
    date_naissance DATE,
    adresse VARCHAR(255),
    mail VARCHAR(100),
    medecin_traitant VARCHAR(100)
);

DROP TABLE IF EXISTS "rdv";

CREATE TABLE rdv (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    date_heure TIMESTAMP,
    patient_id UUID,
    praticien_id UUID,
    FOREIGN KEY (patient_id) REFERENCES patientAdmin(id),
    FOREIGN KEY (praticien_id) REFERENCES praticien(id)
);
