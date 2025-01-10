INSERT INTO specialite (id, label, description)
VALUES ('904e4eb9-5a4e-424d-b239-bf64118c8837', 'Dentiste', 'Spécialiste des dents');


INSERT INTO praticien (id, nom, prenom, adresse, telephone, specialite_id)
VALUES ('fdfe5f21-1e56-4d14-800d-d3c50c1d02af', 'Dupont', 'Pierre', '123 Rue de la Paix', '0123456789', '904e4eb9-5a4e-424d-b239-bf64118c8837');

INSERT INTO patient (nom, prenom, nss, date_naissance, adresse, mail, medecin_traitant)
VALUES ('Georges', 'Victor', '123456789012345', '1990-01-01', '123 Rue de la Paix', 'victorgeorges54@gmail.com', 'Michel');