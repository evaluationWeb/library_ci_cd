# library_ci_cd
Projet de cours PHP POO pour testing et CI/CD.

## Prerequis
- PHP 8.3+
- Composer 2.9+
- MySQL 8+
- Node.js 22+
- Docker + Docker Compose

## Recuperer le projet

### 1. Forker le projet sur GitHub
https://github.com/evaluationWeb/library_ci_cd

## Demarrage avec Docker

### 1. Construire et demarrer les conteneurs
```sh
docker compose up --build -d
```

L'application sera accessible sur :
http://localhost:8080

### 2. Installer les dependances PHP avec Composer
Si besoin, vous pouvez lancer Composer dans le conteneur dedie :
```sh
docker compose run --rm --profile tools composer install
ou docker exec library_app composer install
```

### 3. Base de donnees
La base MySQL est creee automatiquement au demarrage avec le script `db.sql`.

Les donnees sont persistees dans le volume Docker `mysql_data`.

Important : le script d'initialisation n'est execute que lors de la creation initiale du volume. Si vous souhaitez reinitialiser completement la base :
```sh
docker compose down -v
docker compose up --build -d
```

### 4. Arreter les conteneurs
```sh
docker compose down
```

## Demarrage en local

### 1. Installer les dependances
```sh
composer install
npm install
```

### 2. Creer un fichier `.env` a la racine du projet
```env
DATABASE_HOST=localhost
DATABASE_PORT=3306
DATABASE_USERNAME=root
DATABASE_PASSWORD=root
DATABASE_NAME=ci_cd
UPLOAD_DIRECTORY=public/assets/uploads
UPLOAD_PUBLIC_PATH=/assets/uploads
UPLOAD_SIZE_MAX=2097152
UPLOAD_FORMAT_WHITE_LIST=["png","jpg","jpeg","webp"]
```

Vous pouvez aussi conserver l'ancien format suivant pour la base :
```env
DATABASE_HOST=localhost:3306
```

### 3. Creer la base de donnees avec le script `db.sql`

### 4. Demarrer le projet
```sh
php -S 127.0.0.1:8000 -t public
```

Le projet sera accessible avec l'URL suivante :
http://127.0.0.1:8000
