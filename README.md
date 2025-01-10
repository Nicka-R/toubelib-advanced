## Projet Toubelib

### Description

Projet de gestion des rendez-vous médicaux pour les patients et les médecins.

### Installation

1. Cloner le projet
2. Créer toubeelib.env et toubeelibdb.env sur la base des fichiers .env.example
3. Lancer les containers docker

```bash
docker compose up -d
```

4. Installer les dépendances

```bash
docker exec -it toubeelib-api.toubeelib-1 composer install
docker exec -it toubeelib-gateway.toubeelib-1 composer install
```

### Collaborateurs

Odin ALEXANDRE  
Victor GEORGES  
Nicka RATOVOBODO
