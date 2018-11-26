# Créez un blog pour un écrivain
## Quoi ?
Jean Forteroche, acteur et écrivain, travaille actuellement sur son prochain roman, "Billet simple pour l'Alaska". Il souhaite le publier par épisode en ligne sur son propre site.

Jean souhaite avoir son propre outil de blog, offrant des fonctionnalités plus simples.

Elle doit fournir une interface frontend (lecture des billets) et une interface backend (administration des billets pour l'écriture). On doit y retrouver tous les éléments d'un CRUD :

Create : création de billets
Read : lecture de billets
Update : mise à jour de billets
Delete : suppression de billets

Chaque billet doit permettre l'ajout de commentaires, qui pourront être modérés dans l'interface d'administration au besoin, et être "signaler" par les lecteurs pour que ceux-ci remontent plus facilement dans l'interface d'administration pour être modérés.

L'interface d'administration sera protégée par mot de passe. La rédaction de billets se fera dans une interface WYSIWYG basée sur TinyMCE.

## Livrable
Fichiers à fournir
- Code HTML, CSS, PHP et JavaScript
- Export de la base de données MySQL
- Lien vers la page GitHub contenant l'historique des commits
## Soutenance
Vous vous positionnerez comme un développeur présentant pendant 25 minutes son travail à son collègue plus senior dans l’agence web afin de vérifier que le projet peut être présenté tel quel à Jean Forteroche. Cette étape sera suivie de 5 minutes de questions/réponses.

## Comment ?
Vous allez donc devoir développer un moteur de blog en PHP et MySQL.
Vous développerez sur une architecture MVC orienté objet sans utiliser de framework.

```puml {align="center"}
@startuml

skinparam monochrome true
skinparam shadowing false
skinparam handwritten true
skinparam defaultFontName Comic Sans MS

left to right direction

actor Visiteur
actor Editeur

rectangle Blog

Visiteur -- Blog
Editeur -- Blog

@enduml
```

```puml {align="center"}
@startuml
skinparam monochrome true
skinparam shadowing false
skinparam handwritten true
skinparam defaultFontName Comic Sans MS

left to right direction
skinparam packageStyle rectangle

actor Visiteur
actor Editeur
actor DB <<system>>

rectangle Blog {
	Visiteur -- (FrontEnd)
	Editeur -- (BackEnd)
	(FrontEnd) -- DB
	(BackEnd) -- DB
}
@enduml
```

```puml {align="center"}
@startuml
skinparam monochrome true
skinparam shadowing false
skinparam handwritten true
skinparam defaultFontName Comic Sans MS

left to right direction
skinparam packageStyle rectangle
actor Visiteur
package FrontEnd {
	Visiteur -- (Consulter liste des articles Publié)
	Visiteur -- (Lire un article publié)
	Visiteur -- (Lire les commentaires)
	Visiteur -- (Écrire un commentaire)
	Visiteur -- (Signaler un commentaire)
}
actor Editeur
package BackEnd {
	Editeur -- (Consulter liste des articles)
	Editeur -- (Créer un article)
	Editeur -- (Lire un article)
	Editeur -- (Éditer un article)
	Editeur -- (Supprimer un article)
	Editeur -- (Publier un article)
	Editeur -- (Modérer un commentaire)
	(S'authentifier) <.up. (Consulter liste des articles) :include
	(S'authentifier) <.up. (Créer un article) :include
	(S'authentifier) <.up. (Lire un article) :include
	(S'authentifier) <.up. (Éditer un article) :include
	(S'authentifier) <.up. (Supprimer un article) :include
	(S'authentifier) <.up. (Publier un article) :include
	(S'authentifier) <.up. (Modérer un commentaire) :include
}

@enduml
```
