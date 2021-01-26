# Cahier des charges, projet CSU-NVB

Ce document décrit les cas d’utilisation de l’application CSU-NVB.

L’objectif du projet est de créer un site web application fonctionnant au moins sur tablettes et permettant aux membres du Centre de Secours et d’Urgences du Nord Vaudois et de la Broie (CSU-NVB) de gérer les rapports de contrôles journaliers et hebdomadaires usuels.

Le CSU-NVB compte 5 bases : La Vallée-de-Joux, Payerne, Saint-Loup, Sainte-Croix et Yverdon.

Chaque base a 2 ou 3 véhicules (ambulances)

Il y a quelques dizaines de secouristes sur l’ensemble du CSU-NVB, chacun pouvant être appelé à travailler sur plus d'une base.

Dans cette application, on ne considère initialement que deux rôles: utilisateur standard et administrateur (admin).

Le site gère trois types de rapports :

1.	Le stock de stupéfiants par véhicule et par semaine (qu’on appellera « Stup » dans ce document)

2.	Les tâches hebdomadaires de la base (qu’on appellera « Todo » dans ce document)

3.	La remise de garde quotidienne (qu’on appellera « Garde » dans ce document)

# Use Cases

## Se connecter

En arrivant sur le site, le(la) secouriste est invité(e) à se connecter avec ses initiales et son mot de passe, tout en précisant sur quelle base il(elle) se trouve.

Une fois connecté(e), le site propose un accès aux trois types de rapports (Stup, Todo, Garde), pour la base à laquelle le(la) secouriste est rattaché(e).

Pour les admins, un accès à la page d’administration est proposé.

## Gérer des secouristes (admin)

- Login
- Nouveau secouriste. L'administrateur le crée, il a un mot de passe expiré. Quand la personne va sur le site pour la première fois, elle va à la page d'activation, où elle définit son mdp
- Les admins gèrent les secouristes
  - Donner/Retirer le droit d’administrer (sauf à soi-même)

## Gérer une liste de bases (admin)
- Afficher la liste. Clic -> gérer la base
- Créer
- Renommer
- Supprimer (s’il n’y a plus de données associées)

## Gérer les lots de médicaments d'une une base (admin)

- Afficher les lots par médicament
- Permettre d'ajouter des lots

## Remplir un rapport Todo (secouriste, pour une base choisie)
- Liste des rapports clôturés :
  - Date, liste des contributeurs, nom de celui qui a clôturé
  - Clic -> Vue détaillée -> clic bouton download -> format pdf
- Il ne peut pas y avoir plus d'un rapport 'Actif', mais il peut n'y en avoir aucun
- S’il y a un actif, un bouton permet de créer un nouveau rapport 'En préparation'. Il sera basé sur le rapport actif
- Clic sur un rapport de la liste -> vue détaillée
- La page d’édition se présente en 7 colonnes sur un grand affichage et en une colonne sur un petit affichage
- Il y a deux types de cases : toggle et input
- Clic sur une case toggle vierge -> elle devient quittancée par le secouriste
- Clic sur une case toggle déjà quittancée par le secouriste connecté -> elle redevient vierge
- Introduction d’une valeur dans une case input vierge -> elle devient quittancée par le secouriste
- Une case input quittancée par un autre secouriste ne peut pas être modifiée
- Une case input déjà quittancée par le secouriste connecté peut être modifiée. Si la valeur donnée est nulle, la case redevient vierge
- Tous les changements font l'objet d'une inscription dans le log du rapport

## Remplir un rapport Stup (secouriste, pour une base choisie)
- Liste des rapports clôturés
  - Date, liste des contributeurs
  - Clic -> Vue détaillée -> clic bouton download -> format pdf
- Il ne peut pas y avoir plus d'un rapport 'Actif', mais il peut n'y en avoir aucun
- S’il y a un actif, un bouton permet de créer un nouveau rapport 'En préparation'. Il sera basé sur le rapport actif
- Chaque cellule est un input numérique si la journée n’a pas été clôturée
- En bas de chaque jour, il y a un bouton qui permet de clore la journée. Ce bouton demande une confirmation
- le rapport complet est clôturé quand chacun des jours a été clôturé

## Remplir un rapport Garde (secouriste, pour une base choisie)
- Liste des rapports clôturés
  - Date, responsable, équipiers, vehicules, pour le jour et pour la nuit
  - Clic -> Vue détaillée -> clic bouton download -> format pdf
- Il ne peut pas y avoir plus d'un rapport 'Actif', mais il peut n'y en avoir aucun
- S’il y a un actif, un bouton permet de créer un nouveau rapport 'En préparation'. Il sera basé sur le rapport actif


