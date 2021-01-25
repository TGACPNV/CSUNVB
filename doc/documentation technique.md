# Documentation technique CSUNVB

Documentation pour les éventuels nouveaux membres de l'équipe de développement. 

### A quoi sert le site du CSU ? Qui l'utilise et pourquoi ?

Le site internet actuellement en développement sera utilisé par les ambulanciers du CSU Nord Vaudois et Broye.
Il sera utile aux ambulanciers afin de faciliter leurs tâches administratives quotidiennes qui jusqu'à aujourd'hui s'effectuent sur le papier.

Ce site fonctionnera en interne c'est à dire qu'uniquement les membres agréés auront la possibilité d'utiliser le site internet CSUNB. Par membre agréés,s'entend les secouristes.

### Dans quel contexte (technique) fonctionne ce site ?

Le site sera hébérgé par un hébérgeur qui est encore à définir. Une connexion internet sera donc nécessaire pour accèder au site. Celui-ci sera accessible avec un pc ou une tablette, car le site, a terme, devra être entierement responsive.

### Qu'est-ce que je dois faire pour pouvoir essayer ce site ?

Pour l'instant, une version en développement, régulièrement mise à jour,  est disponible à l'adresse [csunvb.mycpnv.ch]. Cependant, il est nécessaire de posséder un identifiant pour s'y connecter. Pour le récupérer, il faut s'adresser au chef de projet, M. Carrel.

### Quelles sont les données / informations que ce site manipule ?

Ce site internet est lié à une base de données qui contient toutes les données nécessaires pour la gestion administrative.
#### Le site est composé de 4 grandes sections :

- Les taches hebdomadaires :
-- Permet la gestion des tâches à effectuer au cours de la semaine


- Les remises de garde : 
-- Permet la gestion des gardes à bord des ambulances (matériel, équipage, remarques pour la garde ...)


- Les stups
-- Permet la gestion des stocks de médicaments dans les ambulances et à la base

- L’administration
-- Permet l’administration du site (utilisateurs, stocks, …)


### De quels composants le site est-il fait ? 

Le site est basé sur la méthode MCV (Model, controleur, vue).
- Il y as un dossier Doc contenant la documentation
- Il y as un dossier public qui contient l'index, le js, le CSS et les assets.
- A la racine on trouve les fichiers globalhelpers.php (les fonctions communes a toutes les parties), path.php (les chemins), policies.php (les politiques d'accès)
- Dans le dossier Vue on retrouve le gabarit et le fichier helpers.php (fonction général d'affichage)

### Quelles technologies est-ce que je dois connaître pour pouvoir développer ce site ? 

Les languages PHP et javascripts sont indispensables pour travailler sur ce projet.
Il faut aussi être à l'aise avec le html et le css pour tout ce qui est de la mise en forme

Il est aussi nécessaire de connaître le sql car il y aura plusieures requête SQL pour interroger la base de donnée.

Le choix de ces languages paraissent évidents pour le développement d'un site internet.

Résumé:
-PHP, javascript
-MySQL
-html, css , bootstrap




### Qu'est-ce que je dois installer sur mon poste de travail pour pouvoir commencer à bosser sur ce site ?
Les logiciels suivant sont ceux que nous avaons utiliser pour travailler. Des alternatives sont possible mais attention à la compatibilité.


- Un environnement de développement: PhpStorm https://www.jetbrains.com/fr-fr/phpstorm/
- Serveur de base de données: MySQL Community Server 8.0.23 https://dev.mysql.com/downloads/mysql/
- Client de base de données: MySQL Workbench (distribué avec MySQL serveur), Heidi SQL https://www.heidisql.com/

### Est-ce qu'on a des conventions de codage ?

La majorité de ce qui est de nature technique est rédigé en anglais: code, commentaires, noms de fonction, de fichiers, de variables, de base de données, de champs, ...

Le formatage du code php suit ce [PhP Style Guide](https://gist.github.com/ryansechrest/8138375)

Les fonctions sont précédées d'un bloc de commentaire qui a la forme suivante:

```
/**
* <nomFonction> : à quoi ça sert
* <paramètre1> : qu’est-ce qu’est + type
* <paramètre2> : qu’est-ce qu’est + type
*…
* return : ce que ça renvoie
**/
```

## M'enfin ... ?

_(Là on arrive aux questions de détails quand Bob ne comprend pas comment ou pourquoi certaines choses sont faites dans le code.
Il s'agit ici de questions d'ordre purement technique et dont la réponse implique plusieurs fichiers parce que dans le cas où un seul fichier est concerné, ce sont les commentaires qui doivent donner l'explication)_

### Qu'est-ce que c'est que ce champ 'slug' dans la table 'status' ?

Un slug est un identifiant sous contrôle du code de l’application. Il se situe entre l’id de base de donnée dont on ne peut jamais présumer de la valeur dans le code et la valeur affichée. Exemple: status ‘Ouvert’, qui a un slug ‘open’ et un id 2. Si je veux sélectionner les rapports ouverts, je fait un select « where slug=‘open’ » . Si l’id de l’état ‘open’ est différent dans une autre db => ça marche, si un jour je veux changer le terme visible de « Ouvert » en « Actif » par exemple, je peux le faire en ne changeant que des données. 

Voir [cette référence](https://medium.com/dailyjs/web-developer-playbook-slug-a6dcbe06c284) (parmi tant d'autres)


