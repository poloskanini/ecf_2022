![alt text](https://www.sngaf.com/wp-content/uploads/2022/09/STUDI-FITNESS-VISUEL-MAIL-1024x614.jpg)


Projet réalisé dans le cadre de l'ECF Décembre 2022.

[TOC]


## Tech Stack

- **Frontend:** HTML / CSS / JavaScript / Bootstrap 5 / WebPack Encore
- **Moteur de Template:** Twig
- **Backend:** MySQL / PHP 8.1.8 / Symfony 6.1 / Composer / ORM Doctrine
- **Base de données :** MySQL - MariaDB


## Authors

Nicolas Barthès
- [Github](https://github.com/poloskanini)
- [Portfolio](https://www.nicolasbarthes.com)


## Pré-Requis

- PHP >= 8.0
- Composer >= 2
- Npm (ou Yarn)
- MySQL - MariaDB
- WebPack Encore (JS / CSS)


<!-- ## Trello du projet
- [Trello](https://trello.com/b/CyZoe9QM) -->

<hr>

## Installation

Suivez les étapes ci-dessous pour installer localement mon projet et le tester.


### Cloner le projet

```bash
  git clone git@github.com:poloskanini/ecf_2022.git
```

### Aller dans le répertoire du projet

```bash
cd ecf_2022
```

## Installation des dépendances

### 1. Symfony

```bash
  composer install
```

### 2. Javascript

```bash
  # Avec npm
  npm install
  # Avec yarn
  yarn install
```

### 3. Compiler les Assets

```bash
  # Avec npm
  npm run build
  npm run watch
  # Avec yarn
  yarn build
  yarn watch
```

## Création de la base de données

Pour créer la base de données, il faut au préalable démarrer le serveur MySQL s'il ne l'est pas.

> 💡<b>INFO :</b>
> Le mot clef `symfony console` peut être remplacé par `php bin/console` si vous n'utilisez pas la CLI de Symfony.


```bash
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
```

### Charger des datas en base de données

```bash
symfony console doctrine:fixtures:load -n
```

## Lancer l'application

Pour démarrer l'application

```bash
  symfony serve:start -d
```

## Explications du sujet

> Dans le cadre de ma formation de Développeur Web & Web Mobile chez STUDI, j’ai dû réaliser un projet complet dans un temps imparti.

> Notre client, une grande marque de salles de sport, veut gérer les droits d'accès et de permissions d'une application web pour ses clients franchisés qui possèdent des salles de sport. Pour ce faire, il nous demande de développer un « panel admin » permettant de gérer ses utilisateurs ainsi que leurs droits et permissions.
> Chaque franchise (PARTENAIRE) a son propre contrat qui dépend de la somme qu’elle verse au client. Plus ou moins de permissions lui seront alors accessibles.

> Chaque PARTENAIRE (franchise) peut posséder plusieurs STRUCTURES (clubs de gym), et il leur donne par défaut un nombre de permissions (outil de planning, newsletters, SMS, etc.) en fonction du contrat qu'il aura souscrit avec la marque.

> Chaque STRUCTURE (club de gym) est rattachée à un partenaire, et elle peut choisir d'activer ou non les permissions données par défaut par le contrat du partenaire.

> Les Partenaires et Structures ont un accès en LECTURE SEULE à leurs informations. Pour toute modification sur leurs permissions ou informations, ils doivent contacter l'administrateur STUDI FITNESS qui est le seul à avoir les pleins pouvoirs.

> Le projet a été réalisé avec le framework SYMFONY.
La partie FRONT-END a été réalisée en HTML / CSS / JavaScript / Bootstrap / Twig / Encore.
La partie BACK-END a été réalisée en MySQL / Symfony / Composer, ainsi que l’ORM Doctrine pour la création et gestion de la BDD (fichiers de migration).


## Cahier des charges

>	L’équipe technique devra pouvoir ajouter, éditer, voir et supprimer les comptes des nouveaux partenaires et structures qui rejoindront la marque de sport, ainsi que modifier et mettre à jour l’ensemble des permissions et des droits qui leur sont rattachées.

>	Chaque partenaire/structure devra pouvoir être désactivé(e) en 1 clic tout en le(la) conservant en base de données. Si l’utilisateur est désactivé, alors il ne pourra pas se connecter à l’application en lecture seule pour visualiser son compte.

>	Tout changement des permissions du partenaire devra automatiquement être appliqué aux permissions de la structure qui y est rattachée.

>	Les partenaires et structures recevront un email pour chaque mise à jour de leur compte. Le partenaire sera également informé par un mail de toute modification sur l’une de ses structures.

>	A la création d’un nouveau compte, l’utilisateur reçoit un mail avec un mot de passe sécurisé qu’il est invité à remplacer en cliquant sur un lien où il pourra redéfinir son mot de passe. Il disposera de 3h pour le modifier à partir du moment où il aura demandé sa réinitialisation.

>	L’application devra demander une confirmation de sécurité à l’administrateur pour toute création, modification ou suppression d’un utilisateur.

>	Une barre de recherche permettra à l’administrateur de trouver rapidement un utilisateur en tapant les premières lettres de son prénom et d’afficher dynamiquement le résultat. De même, on doit pouvoir trier la liste des partenaires et structures afin de ne présenter que les éléments activés ou désactivés, sans rechargement de la page également.

>	L’équipe technique d’administration qui utilisera notre application n’a aucune connaissance en développement, l’outil que nous devons créer doit donc être facile à prendre en main pour n’importe quel utilisateur (UX/UI).

>	Il n’y a pas de charte graphique définie, seulement des suggestions, le client nous laisse donc les mains libres pour personnaliser et créer l’univers de la marque.

>	Notre application devra bien entendue être responsive-design et pensée mobile-first dans sa conception.