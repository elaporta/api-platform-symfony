# Symfony
https://api-platform.com/docs/symfony/

## Install Symfony CLI
- **Url:** https://symfony.com/download
- **curl:** ``curl -sS https://get.symfony.com/cli/installer | bash``
- **homebrew:** ``brew install symfony-cli/tap/symfony-cli``

## Check Requirements
- ``symfony check:requirements``

## Creating Symfony Application
- **web:** ``symfony new my_project_directory --version="7.3.x" --webapp``
- **api:** ``symfony new my_project_directory --version="7.3.x"``

## Start Application
- ``cd my-project/``
- ``symfony server:start``

## Add Api Packages
- ``composer require api``

## Add Maker
- ``composer require maker --dev``

## Add Foundry Orm Fixtures
- ``composer require foundry orm-fixtures --dev``

## Add Debug
- ``composer require debug``

## Create a new Entity
- ``./bin/console make:entity``

## Set Database connection

- Run Docker Image with DB: ``docker compose up -d``
- Or set local DB by editing .env
- Example: ``DATABASE_URL="mysql://<USER>:<PASSWORD>@127.0.0.1:3306/<DB_NAME>?serverVersion=8.0.40&charset=utf8mb4"``

## DB Migrations
- Drop Database: ``symfony console doctrine:database:drop --force``
- Create Database: ``symfony console doctrine:database:create``
- Create Migrations: ``symfony console make:migration``
- Run Migrations: ``symfony console doctrine:migrations:migrate``

## Create DB Factory
- ``./bin/console make:factory``

## Load Fixtures in DB
- ``symfony console doctrine:fixtures:load``

## Clear cache
- ``php bin/console cache:clear``

## See Current Api Platform Configuration
- ``./bin/console debug:config api_platform``

## See All Api Platform Configuration Available
- ``./bin/console config:dump api_platform``

## Create user entity and security
- ``./bin/console make:user``