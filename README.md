# Ina Zaoui

Pour se connecter avec le compte de Ina, il faut utiliser les identifiants suivants:
- identifiant : `ina`
- mot de passe : `password`

Vous trouverez dans le fichier `backup.zip` un dump SQL anonymisé de la base de données et toutes les images qui se trouvaient dans le dossier `public/uploads`.
Faudrait peut être trouver une meilleure solution car le fichier est très gros, il fait plus de 1Go.


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


## Launch tests:
- go to the root folder
- bin/phpunit



## TODO:

- Upgrade symfony en 7.1 **CHECK**
- mise a jour des dependances du composer**CHECK**
- creer la base de donnée **CHECK**
- modifier l'entity User **CHECK**
- creer des fixtures standard **CHECK**
- reorganiser les controllers **CHECK**
- refactor du header (navigation foireuse) **CHECK**
- gerer l'espace admin pour l'admin **CHECK**
- ecrire les tests en TDD (avant le rajout de feature) (100% de tests couvert on est des bogoss !)
- Rajouter une POPUP "etes vous sur?" pour tout suppresion d'entité.
- Bloquer l’accès d’un invité
- gerer l'espace admin pour les invités
- creer des fixtures de test
- ecrire l'integration continue

An exception occurred while executing a query: SQLSTATE[23503]: Foreign key violation: 7 ERROR: update or delete on table "user" violates foreign key constraint "fk_6a2ca10ca76ed395" on table "media"
DETAIL: Key (id)=(130) is still referenced from table "media".

## TODO FOR TEST :

- un utilisateur peut se connecter peut importe son role
- le role admin peut creer/modifier/supprimer/shutOff des comptes
- le role admin peut creer/modifier/supprimer des albums
- le role admin peut creer/modifier/supprimer des medias
- Les pages frontOffice sont toutes disponibles pour tout le monde
- les pages Admin sont dispo pour les utilisateurs connectés
- Si l'utilisateur est Admin, il a acces a la page des utilisateurs
- Si l'user est Admin, il a access a tout les media + albums
- Si l'utilisateur est user, il a access à ses propres medias et album (et c'est tout)

-Un Album n'est pas link sur un user, modifier son entity?
-Un media peut etre supprimé par n'importe quel role, rajouter condition dans le MediaForm?
- changer les fixtures de test pour renommer l'admin "ADMIN"
- Changer les status code 200 en cas d'erreur (par ex : add form) OU ALORS ...
- plutot crawler la page pour recuperer les erreurs