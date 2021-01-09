# Documentation technique

Bob est un développeur qui vient de rejoindre l'équipe de développement du TRUC. Ce document répond aux questions qu'il se pose

### A quoi sert ce TRUC ? Qui l'utilise et pourquoi ?

### Dans quel contexte (technique) fonctionne ce TRUC ?

_(est-ce qu'il a besoin de réseau ? de Wifi ? d'un serveur ? d'internet ? et pourquoi ? ...)_

### Qu'est-ce que je dois faire pour pouvoir essayer ce TRUC ?

### Quelles sont les données / informations que ce TRUC manipule ?

### De quels composants le TRUC est-il fait ? 

_(Comment est-ce qu'ils interagissent entre eux ?)_

### Quelles technologies est-ce que je dois connaître pour pouvoir développer ce TRUC ? 

_(Lesquelles est-ce que je dois maîtriser ?)_

_(Pourquoi est-ce qu'on les a choisies ?)_

### Qu'est-ce que je dois installer sur mon poste de travail pour pouvoir commencer à bosser sur ce TRUC ?

_(Les outils, les données et le code source)_

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


