## TODO:

- Upgrade symfony en 7.1 **CHECK**
- mise a jour des dependances du composer**CHECK**
- creer la base de donnée **CHECK**
- modifier l'entity User **CHECK**
- creer des fixtures standard **CHECK**
- reorganiser les controllers **CHECK**
- refactor du header (navigation foireuse) **CHECK**
- gerer l'espace admin pour l'admin **CHECK**
- Bloquer l’accès d’un invité **CHECK**
- gerer l'espace admin pour les invités **CHECK**
- creer des fixtures de test **CHECK**
- ecrire les tests en TDD (avant le rajout de feature) (100% de tests couvert on est des bogoss !)
- Rajouter une POPUP "etes vous sur?" pour tout suppresion d'entité.
- ecrire l'integration continue


## TODO FOR TEST :

- un utilisateur peut se connecter peut importe son role **CHECK**
- le role admin peut creer/modifier/supprimer/shutOff des comptes **CHECK**
- le role admin peut creer/modifier/supprimer des albums **CHECK**
- le role admin peut creer/modifier/supprimer des medias **CHECK**
- Les pages frontOffice sont toutes disponibles pour tout le monde **CHECK**
- les pages Admin sont dispo pour les utilisateurs connectés **CHECK**
- Si l'utilisateur est Admin, il a acces a la page des utilisateurs **CHECK**
- Si l'user est Admin, il a access a tout les media + albums **CHECK**
- Si l'utilisateur est user, il a access à ses propres medias et album (et c'est tout) **CHECK**

-Un Album n'est pas link sur un user, modifier son entity? **CHECK**
-Un media peut etre supprimé par n'importe quel role, rajouter condition dans le MediaForm? **CHECK**
- changer les fixtures de test pour renommer l'admin "ADMIN" **CHECK**
- Installer le --test-coverage **CHECK**
- Changer les status code 200 en cas d'erreur (par ex : add form) OU ALORS ...
- plutot crawler la page pour recuperer les erreurs
- Traduire le texte en fr
- Sortir les utilisateur par ordre alphabetique

- Intégrez un mécanisme permettant à Ina de révoquer l'accès des invités sélectionnés,  **CHECK**
- ce qui entraînera le non-affichage de leurs photos sur la plateforme, **CHECK**
- et l’impossibilité de se connecter sur leur accès. **CHECK**