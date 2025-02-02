## Projet Toubelib

### Description

Projet de gestion des rendez-vous médicaux pour les patients et les médecins.

### Installation

1. Cloner le projet
2. A la racine du projet créer toubeelib.env sur la base du fichier toubeelib.env.example
3. Toujours à la racine du projet créer les .env des pour chaque bases de données sur la base du fichier <databasedb>.env.example.  
   Vous devriez avoir

- `praticiendb.env`,
- `authdb.env`,
- `patientdb.env`,
- `rdvdb.env`

4. Créer les .ini pour chaque service sur la base du fichier <database>.db.ini.example (praticien.db.ini, auth.db.ini et patient.db.ini, rdv.db.ini)  
   Dans /app-rdv/config vous devriez avoir

- `rdv.db.ini`

Et dans /app-praticiens/config

- `praticien.db.ini`
- `rdv.db.ini`

Assurez vous que les fichiers .env et .ini sont bien configurés, notamment pour les user et password des bases de données.

5. Lancer les containers docker

```bash
docker compose up -d
```

4. Installer les dépendances

```bash
docker compose exec api.toubeelib bash -c "composer install"
docker compose exec gateway.toubeelib bash -c "composer install"
docker compose exec api.praticiens bash -c "composer install"
docker compose exec api.rdv bash -c "composer install"

```

### Configuration

1. Ajouter les domaines dans le fichier hosts (sur windows, le fichier se trouve dans C:\Windows\System32\drivers\etc\hosts)

```
# toubeelib
127.0.0.1 api.praticiens
127.0.0.1 api.rdv
127.0.0.1 api.toubeelib
127.0.0.1 gateway.toubeelib
```

2. (optionnel) Vérifier que les domaines sont bien configurés

```bash
curl http://api.praticiens:6090/
curl http://api.rdv:6100/
curl http://api.toubeelib:6080/
curl http://gateway.toubeelib:6081/
```

### Fonctionnalités

- [x] Séparation de l'application en microservices (Gateway, Auth, Praticiens, RDV)
- [x] Mise en place d'une action générique dans le Gateway
- [x] Communication entre les microservices avec Guzzle
- [x] Séparation des bases de données (Auth, Praticiens et Spécialités, RDV)
- [x] Authentification des utilisateurs (JWT)
- [x] Mise en place d'un système de communication pour notifier les utilisateurs et les praticiens en fonction des actions effectuées sur les rendez-vous
- [x] Amélioration de l'API REST (Nom des routes et HATEOAS)
- [x] Mise en place d'un système de notification par email via RabbitMQ


### Collaborateurs

Odin ALEXANDRE  
Victor GEORGES  
Nicka RATOVOBODO
