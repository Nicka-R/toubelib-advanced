DROP TABLE IF EXISTS "specialite";
CREATE TABLE "public"."specialite" (
    "id" uuid DEFAULT uuid_generate_v4() NOT NULL,
    "label" character varying(100),
    "description" text,
    CONSTRAINT "specialite_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

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
