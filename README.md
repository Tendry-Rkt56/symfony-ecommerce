# symfony-ecommerce
Application web de vente en ligne de produits alimentaires, construite avec le framework Symfony 7

## Fonctionnalités
- Inscription et connexion des utilisateurs
- Gestion des commandes pour les utilisateurs
- Simulation d'achats
- Interface d'administration pour gérer les produits et les commandes

## Prérequis
- PHP 8.2.0 ou supérieur
- Composer
- Symfony CLI
- MySQL ou un autre SGBD compatible

## Installation
Suivez les étapes ci-dessous pour configurer le projet localement :

1. **Cloner le dépôt**

   ```bash
   git clone https://github.com/Tendry-Rkt56/symfony-ecommerce.git

2. **Naviguer dans le dossier du projet**

3. **Installer les dépéndances**
    
    ```bash
    composer install

4. **Ouvrez .env et modifiez les paramètres pour votre configuration de base de données locale** 

5. **Créer la base de donnée**

    ```bash
    php bin/console doctrine:database:create

6. **Appliquer les migrations**
    
    ```bash
    php bin/console doctrine:migrations:migrate

7. **Lancez les fixtures pour peupler la base de données**

    ```bash
    php bin/console doctrine:fixtures:load

8. **Lancez le serveur local Symfony**

    ```bash
    symfony server:start

## Informations de connexion 
  ### Administrateur
     - URL de connexion : 'http://localhost:8000/login'
     - Email : admin@gmail.com
     - Mot de passe : 0000

## Structure du Projet
- `src/` : Code source de l'application
- `templates/` : Templates Twig pour les vues
- `public/` : Fichiers publics (assets, index.php, etc.)
- `config/` : Fichiers de configuration
- `migrations/` : Migrations de base de données
- `src/Entity/` : Entités Doctrine
- `src/Repository/` : Répertoires des entités
- `src/Controller/` : Contrôleurs de l'application

## Contributions
Les contributions sont les bienvenues ! Veuillez ouvrir une issue ou soumettre une pull request pour toute amélioration ou correction de bugs.

## Contact
- **Nom** : Tendry Zéphyrin
- **Email** : tendryzephyrin@gmail.com
- **GitHub** : [Tendry-Rkt56](https://github.com/Tendry-Rkt56)
