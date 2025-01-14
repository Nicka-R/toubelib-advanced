INSERT INTO "specialite" ("id", "label", "description") VALUES
('904e4eb9-5a4e-424d-b239-bf64118c8837', 'Dentiste', 'Spécialiste des dents'),
('7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d', 'Cardiologue', 'Spécialiste du cœur'),
('b2c3d4e5-6789-01bc-def2-3456789012cd', 'Dermatologue', 'Spécialiste de la peau');

INSERT INTO "praticien" ("id", "nom", "prenom", "adresse", "telephone", "specialite_id") VALUES
('fdfe5f21-1e56-4d14-800d-d3c50c1d02af', 'Dupont', 'Pierre', '123 Rue de la Paix', '0123456789', '904e4eb9-5a4e-424d-b239-bf64118c8837'),
('2a8a9689-adbe-4859-84ac-a109e832339f', 'René', 'Morneau', '22 Rue Marie De Médicis', '0365522923', '904e4eb9-5a4e-424d-b239-bf64118c8837'),
('9c0d1e2f-3a4b-4c5d-7e8f-9a0b1c2d3e4f', 'Lemoine', 'Claire', '303 Rue de la Santé', '0456789012', '7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d'),
('0d1e2f3a-4b5c-4d6e-8f9a-0b1c2d3e4f5a', 'Moreau', 'Paul', '404 Avenue des Fleurs', '0567890123', 'b2c3d4e5-6789-01bc-def2-3456789012cd'),
('4b5c6d7e-8f9a-4b0c-2d3e-4f5a6b7c8d9e', 'Bernard', 'Julie', '505 Boulevard des Arts', '0678901234', 'b2c3d4e5-6789-01bc-def2-3456789012cd');