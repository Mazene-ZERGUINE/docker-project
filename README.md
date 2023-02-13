# Projet Docker

- [Technologies](#technologies)
- [Versioning](#versioning)
- [Fonctionnalités de l’application](#fonctionnalits-de-lapplication)

## Introduction
Le projet est une application web.<br/>
Il permet à un lecteur d’enregistrer les livres qu’il a lus dans une bibliothèque virtuelle.

## Technologies
- **Langages** :
  - front-end : HTML, Scss, TypeScript
  - back-end : PHP
- **Framework** :
  - front-end : Angular
  - back-end : Symfony
- **Base de données** :
  - SQL : PostgreSQL
- **Outils de développement** :
  - Versioning: Git
  - Conteneurisation : Docker

## Versioning
Concernant la convention de nommage des _commits_, la spécification "[conventional commits](https://www.conventionalcommits.org/en/v1.0.0/)" a été choisie, ainsi que l'anglais pour la langue.<br/><br/>

Au début du projet pour le processus Git, nous avons décidé de suivre le "Gitflow" avec des "pull requests" : il y a une branche de développement et une branche principale.<br/>
- Le processus se déroule comme ceci :
  - le développeur crée des branches à partir de la branche de développement
  - le développeur fait une _pull request_ pour demander une revue de son travail à ses pairs
  - les pairs doivent approuver la demande
  - le demandeur pourra fusionner ses branches sur la branche de développement
  - le chef d'équipe ou un développeur peut fusionner la branche de développement sur la branche principale.<br/><br/>

Cela nous permet : de réduire les erreurs humaines, de nous corriger et de protéger notre produit final.

## Fonctionnalités de l’application
- Afficher ses livres
