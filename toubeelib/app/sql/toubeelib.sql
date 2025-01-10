-- Adminer 4.8.1 PostgreSQL 16.4 (Debian 16.4-1.pgdg120+1) dump

DROP TABLE IF EXISTS "patient";
CREATE TABLE "public"."patient" (
    "id" uuid DEFAULT uuid_generate_v4() NOT NULL,
    "nom" character varying(100),
    "prenom" character varying(100),
    "nss" character varying(15),
    "date_naissance" date,
    "adresse" character varying(255),
    "mail" character varying(100),
    "medecin_traitant" character varying(100),
    CONSTRAINT "patient_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

INSERT INTO "patient" ("id", "nom", "prenom", "nss", "date_naissance", "adresse", "mail", "medecin_traitant") VALUES
('929b1929-1536-4752-8308-3e5fb85d6ad7', 'Georges', 'Victor', '123456789012345', '1990-01-01', '123 Rue de la Paix', 'victorgeorges54@gmail.com', 'Michel'),
('2b3c4d5e-6f7a-8b9c-0d1e-2f3a4b5c6d7e', 'Martin', 'Jean', '234567890123456', '1985-05-15', '456 Avenue des Champs', 'jean.martin@example.com', 'Dupont'),
('3c4d5e6f-7a8b-9c0d-1e2f-3a4b5c6d7e8f', 'Durand', 'Marie', '345678901234567', '1978-03-22', '789 Boulevard de la République', 'marie.durand@example.com', 'Lemoine'),
('c3d4e5f6-7890-12cd-ef34-5678901234de', 'Petit', 'Luc', '456789012345678', '1992-07-30', '101 Rue de la Liberté', 'luc.petit@example.com', 'Moreau'),
('d4e5f6h7-8901-23de-f456-7890123456ef', 'Leroy', 'Sophie', '567890123456789', '1980-11-11', '202 Rue de la Gare', 'sophie.leroy@example.com', 'Bernard');

DROP TABLE IF EXISTS "specialite";
CREATE TABLE "public"."specialite" (
    "id" uuid DEFAULT uuid_generate_v4() NOT NULL,
    "label" character varying(100),
    "description" text,
    CONSTRAINT "specialite_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

INSERT INTO "specialite" ("id", "label", "description") VALUES
('904e4eb9-5a4e-424d-b239-bf64118c8837', 'Dentiste', 'Spécialiste des dents'),
('7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d', 'Cardiologue', 'Spécialiste du cœur'),
('b2c3d4e5-6789-01bc-def2-3456789012cd', 'Dermatologue', 'Spécialiste de la peau');

DROP TABLE IF EXISTS "praticien";
CREATE TABLE "public"."praticien" (
    "id" uuid DEFAULT uuid_generate_v4() NOT NULL,
    "nom" character varying(100),
    "prenom" character varying(100),
    "adresse" character varying(255),
    "telephone" character varying(20),
    "specialite_id" uuid,
    CONSTRAINT "praticien_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

INSERT INTO "praticien" ("id", "nom", "prenom", "adresse", "telephone", "specialite_id") VALUES
('fdfe5f21-1e56-4d14-800d-d3c50c1d02af', 'Dupont', 'Pierre', '123 Rue de la Paix', '0123456789', '904e4eb9-5a4e-424d-b239-bf64118c8837'),
('2a8a9689-adbe-4859-84ac-a109e832339f', 'René', 'Morneau', '22 Rue Marie De Médicis', '0365522923', '904e4eb9-5a4e-424d-b239-bf64118c8837'),
('9c0d1e2f-3a4b-4c5d-7e8f-9a0b1c2d3e4f', 'Lemoine', 'Claire', '303 Rue de la Santé', '0456789012', '7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d'),
('0d1e2f3a-4b5c-4d6e-8f9a-0b1c2d3e4f5a', 'Moreau', 'Paul', '404 Avenue des Fleurs', '0567890123', 'b2c3d4e5-6789-01bc-def2-3456789012cd'),
('4b5c6d7e-8f9a-4b0c-2d3e-4f5a6b7c8d9e', 'Bernard', 'Julie', '505 Boulevard des Arts', '0678901234', 'b2c3d4e5-6789-01bc-def2-3456789012cd');

DROP TABLE IF EXISTS "rdv";
CREATE TABLE "public"."rdv" (
    "id" uuid DEFAULT uuid_generate_v4() NOT NULL,
    "date_heure" timestamp,
    "patient_id" uuid,
    "praticien_id" uuid,
    CONSTRAINT "rdv_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

INSERT INTO "rdv" ("id", "date_heure", "patient_id", "praticien_id") VALUES
('7e913093-e42f-4b08-813d-0ec0521a1301', '2025-01-17 16:00:00', '929b1929-1536-4752-8308-3e5fb85d6ad7', '2a8a9689-adbe-4859-84ac-a109e832339f'),
('8f0241a2-b3c4-5d6e-7f89-0123456789ab', '2025-01-13 09:00:00', '2b3c4d5e-6f7a-8b9c-0d1e-2f3a4b5c6d7e', 'fdfe5f21-1e56-4d14-800d-d3c50c1d02af'),
('5e6f7a8b-9c0d-4e1f-3a4b-5c6d7e8f9a0b', '2025-01-13 11:30:00', '3c4d5e6f-7a8b-9c0d-1e2f-3a4b5c6d7e8f', '4b5c6d7e-8f9a-4b0c-2d3e-4f5a6b7c8d9e');

ALTER TABLE ONLY "public"."praticien" ADD CONSTRAINT "praticien_specialite_id_fkey" FOREIGN KEY (specialite_id) REFERENCES specialite(id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."rdv" ADD CONSTRAINT "rdv_patient_id_fkey" FOREIGN KEY (patient_id) REFERENCES patient(id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."rdv" ADD CONSTRAINT "rdv_praticien_id_fkey" FOREIGN KEY (praticien_id) REFERENCES praticien(id) NOT DEFERRABLE;

-- 2025-01-10 19:39:35.094178+00