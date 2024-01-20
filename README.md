# Application de saisie de performances en musculation
Projet personnel ayant pour but de faciliter le suivi de ses résultats en musculation.  
On peut y fabriquer ses programmes personnalisés et saisir ses performances sur chaque exercice, de manière à garder un historique.  
La première version a été codée en PHP. Elle a ensuite été traduite en React JS + Laravel.  

## Structure
**interface_react :** contient le projet React  
**apis_laravel :** contient le projet Laravel avec ses APIs  
**OLD-FROM_SCRATCH :** contient l'ancien projet, codé en PHP  

## Lancement
### Laravel
- Lancer sa base de données
- Aller dans le dossier de Laravel
- Configurer le fichier .env avec les identifiants de la BDD
- `php artisan migrate --seed` pour peupler la BDD
- `php artisan serve` pour lancer le serveur

### React
- Aller dans le dossier de React
- `npm install` pour installer les dépendances
- `npm start`pour lancer le serveur local
