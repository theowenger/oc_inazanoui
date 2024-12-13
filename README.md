# Ina Zaoui

## Prerequisites

- PHP >= 8.1
- Composer
- PostgreSQL
- internal serveur (Nginx/Apache)
- Git

## Setup project

- git clone https://github.com/theowenger/oc_inazanoui.git
- go into the root folder of the project
- composer install
- create relationship database (.env.local)
- symfony server:start
- go to your localhost (ex: http://127.0.0.1:8000)

## Launch fixture:

- go to the root folder
- symfony console doctrine:fixtures:load
- You can choose the group for your test : --group=test OR --group=app
- Every group charging different fixtures data
- if your setup your DB, the fixtures will play

## Launch fixtures for test:

- go to the root folder
- php bin/console doctrine:fixtures:load --group=test --env=test

## Create Alias to Help launching tests 
- alias run-tests="php bin/console doctrine:fixtures:load --group=test --env=test -n && XDEBUG_MODE=coverage bin/phpunit --coverage-html ./result-test"


## Launch tests:
- go to the root folder
- run-tests