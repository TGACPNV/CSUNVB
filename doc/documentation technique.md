# Documentation technique CSUNVB

Documentation pour les éventuels nouveaux membres de l'équipe de développement. 

### A quoi sert le site du CSU ? Qui l'utilise et pourquoi ?

Le site internet actuellement en développement sera utilisé par les ambulanciers du CSU Nord Vaudois et Broye.
Il sera utile aux ambulanciers afin de faciliter leures tâches administratives quotidienne qui jusqu'à aujourd'hui s'effectuent sur le papier.

Ce site fonctionnera en interne c'est à dire qu'uniquement les membres agréer auront le privilège d'utiliser le site internet CSUNVB, par membre agréer on entend les secouristes.

### Dans quel contexte (technique) fonctionne ce site ?

Le site sera hébérger par un hébérgeur qui est encore à définir, cepandant une connexion internet sera nécessaire pour accèder au site. On pourra y accèder avec un pc ou une tablette car le site est responsive.
### Qu'est-ce que je dois faire pour pouvoir essayer ce site ?

Pour l'instant une version d'essai régulièrement mise à jour est disponible sur CSUNVB.mycpnv.ch
Cependant il faudra demander un identifiant au chef de projet de manière à vous connecter et essayer le prototype vu qu'il faut être connecté pour accèder au contenu

### Quelles sont les données / informations que ce site manipule ?

Ce site internet est lié à une base de donnée qui va contenir plusieures choses comme les utilisateurs (secouristes), Les médicaments, les différentes ambulances.

### De quels composants le site est-il fait ? 

-PHP, javascript
-MySQL
-html, css
_(Comment est-ce qu'ils interagissent entre eux ?)_

### Quelles technologies est-ce que je dois connaître pour pouvoir développer ce site ? 

Les languages PHP et javascripts sont indispensables pour travailler sur ce projet.
Il faut aussi être à l'aise avec le html et le css pour tout ce qui est de la mise en forme

Il est aussi nécessaire de connaître le sql car il y aura plusieures requête SQL pour interroger la base de donnée.

Le choix de ces languages paraissent évidents pour le développement d'un site intenet.

### Qu'est-ce que je dois installer sur mon poste de travail pour pouvoir commencer à bosser sur ce site ?
Les logiciels suivant sont nécessaires pour pouvoir travailler:

- Un environnement de développement: PhpStorm https://www.jetbrains.com/fr-fr/phpstorm/
- Wampserver https://www.wampserver.com/en/download-wampserver-64bits/
- 

### Est-ce qu'on a des conventions de codage ?

Tout ce qui est de nature technique est rédigé en anglais: code, commentaires, noms de fonction, de fichiers, de variables, de base de données, de champs, ...

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


