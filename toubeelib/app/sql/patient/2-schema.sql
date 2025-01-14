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

