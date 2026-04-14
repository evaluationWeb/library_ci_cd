# library_ci_cd
projet de cours PHP POO pour testing et CI/CD  

## prés-requis :  
- PHP 8.3 +, 
- Composer 2.9 +,
- Mysql 8 +,
- nodeJS 22 +  

## Pour récupérer le projet :  

### 1 forker le projet sur github :
https://github.com/evaluationWeb/library_ci_cd  

### 2 installer les dépendances :  
```sh
# saisir la commande suivante
composer install
```

### 3 Créer un fichier .env (à la racine du projet)
```env
# personnaliser avec vos valeurs
DATABASE_HOST=localhost:3306
DATABASE_USERNAME=root
DATABASE_PASSWORD=root
DATABASE_NAME=ci_cd
```

### 4 Créer la base de données avec le script db.sql

### 5 démarrer le projet 
```sh
# saisir la commande suivante
php -S 127.0.0.1:8000 -t public
```
Le projet sera accessible avec l'URL suivante : http://127.0.0.1:8000 
