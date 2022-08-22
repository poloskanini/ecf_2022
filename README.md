# Studi Fitness

Projet réalisé dans le cadre de l'ECF Décembre 2022.


## Tech Stack

**Frontend:** HTML / CSS / JavaScript / Bootstrap 5
***Moteur de Template:** Twig
**Backend:** PHP, Symfony 6
**Base de données :** MySql
**Serveur local :** MAMP


## Authors

- [@Nicolas Barthès](https://github.com/poloskanini)


## Pré-Requis

- PHP >= 8.0
- Composer >= 2
- Npm (ou Yarn)
- MySql
- WebPack Encore


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
  yarn
```

### 3. Compiler les Assets

```bash
  # Avec npm
  npm run build
  # Avec yarn
  yarn build
```

## Création de la base de données

Pour créer la base de données, il faut au préalable démarrer le serveur MySql si il ne l'est pas.

> 👍 Info

> Le mot clef `symfony console` peut être remplacé par `php bin/console` si vous n'utilisez pas le CLI de Symfony.


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

## Se connecter à l'application

| Email                               | Mot de passe | Role       | Description                               |
|-------------------------------------|--------------|------------|-------------------------------------------|
| admin@admin.fr                      | admin        | ADMIN      | Administrateur principal de l'application |
