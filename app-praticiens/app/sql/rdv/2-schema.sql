DROP TABLE IF EXISTS "rdv";
CREATE TABLE "public"."rdv" (
    "id" uuid DEFAULT uuid_generate_v4() NOT NULL,
    "date_heure" timestamp,
    "patient_id" uuid,
    "praticien_id" uuid,
    CONSTRAINT "rdv_pkey" PRIMARY KEY ("id")
) WITH (oids = false);
