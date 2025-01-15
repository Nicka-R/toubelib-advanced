## Projet Toubelib

### Description

Projet de gestion des rendez-vous médicaux pour les patients et les médecins.

### Installation

1. Cloner le projet
2. Créer toubeelib.env sur la base des fichiers .env.example
3. Créer les .env à la racine du projet des pour chaque base de données sur la base du fichier database.env.example (praticiendb.env, authdb.env et patientdb.env, rdvdb.env)
4. Créer les .ini pour chaque service sur la base du fichier database.db.ini.example (praticien.db.ini, auth.db.ini et patient.db.ini, rdv.db.ini)
   Assurez vous que les fichiers .env et .ini sont bien configurés, notamment pour les user et password des bases de données.
5. Lancer les containers docker

```bash
docker compose up -d
```

4. Installer les dépendances

```bash
docker exec -it projet-toubelib-api.toubeelib-1 composer install
docker exec -it projet-toubelib-gateway.toubeelib-1 composer install
docker exec -it projet-toubelib-api.praticiens-1 composer install
```

### Configuration

1. Ajouter les domaines dans le fichier hosts (sur windows, le fichier se trouve dans C:\Windows\System32\drivers\etc\hosts)

```
127.0.0.1 api.praticiens
127.0.0.1 api.toubeelib
127.0.0.1 toubeelib.gateway
```

2. (optionnel) Vérifier que les domaines sont bien configurés

```bash
curl http://api.praticiens:6090/
curl http://api.toubeelib:6080/
curl http://gateway.toubeelib:6081/
```

### Collaborateurs

Odin ALEXANDRE  
Victor GEORGES  
Nicka RATOVOBODO
