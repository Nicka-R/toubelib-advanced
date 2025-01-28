## Projet Toubelib

### Description

Projet de gestion des rendez-vous médicaux pour les patients et les médecins.

### Installation

1. Cloner le projet
2. Créer toubeelib.env sur la base des fichiers .env.example
3. Créer les .env à la racine du projet des pour chaque base de données sur la base du fichier database.env.example (praticiendb.env, authdb.env et patientdb.env, rdvdb.env)
4. Créer les .ini pour chaque service sur la base du fichier database.db.ini.example (praticien.db.ini, auth.db.ini et patient.db.ini, rdv.db.ini)
   Assurez vous que les fichiers .env et .ini sont bien configurés, notamment pour les user et password des bases de données.
Par exemple pour le service app-auth, il faut placer le fichier "auth.db.ini" dans le repertoire app-auth/app/config
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
docker compose exec api.auth bash -c "composer install"

```

### Configuration

1. Ajouter les domaines dans le fichier hosts (sur windows, le fichier se trouve dans C:\Windows\System32\drivers\etc\hosts)

2. Sous Linux, le fichier est /etc/hosts

```
127.0.0.1 api.praticiens
127.0.0.1 api.toubeelib
127.0.0.1 gateway.toubeelib
127.0.0.1 api.auth
```

2. (optionnel) Vérifier que les domaines sont bien configurés

```bash
curl http://api.praticiens:6090/
curl http://api.toubeelib:6080/
curl http://gateway.toubeelib:6081/
curl http://api.auth:6110/
```

### Collaborateurs

Odin ALEXANDRE  
Victor GEORGES  
Nicka RATOVOBODO
