---
export_on_save:
  markdown: true
markdown:
  image_dir: /doc/img
  path: /README.md
  ignore_from_front_matter: false
  absolute_image_path: true
toc:
  depth_from: 2
  depth_to: 6
  ordered: false
---

# Créez un blog pour un écrivain

[TOC]
## Besoin
### Quoi ?

Jean Forteroche, acteur et écrivain, travaille actuellement sur son prochain roman, "Billet simple pour l'Alaska". Il souhaite le publier par épisode en ligne sur son propre site.

Jean souhaite avoir son propre outil de blog, offrant des fonctionnalités plus simples.

Elle doit fournir une interface frontend (lecture des billets) et une interface backend (administration des billets pour l'écriture). On doit y retrouver tous les éléments d'un CRUD.

Chaque billet doit permettre l'ajout de commentaires, qui pourront être modérés dans l'interface d'administration au besoin, et être "signaler" par les lecteurs pour que ceux-ci remontent plus facilement dans l'interface d'administration pour être modérés.

L'interface d'administration sera protégée par mot de passe. La rédaction de billets se fera dans une interface WYSIWYG basée sur TinyMCE.

### Livrable

Fichiers à fournir

-   Code HTML, CSS, PHP et JavaScript
-   Export de la base de données MySQL
-   Lien vers la page GitHub contenant l'historique des commits

### Soutenance
Vous vous positionnerez comme un développeur présentant pendant 25 minutes son travail à son collègue plus senior dans l’agence web afin de vérifier que le projet peut être présenté tel quel à Jean Forteroche. Cette étape sera suivie de 5 minutes de questions/réponses.

### Comment ?

Vous allez donc devoir développer un moteur de blog en PHP et MySQL.
Vous développerez sur une architecture MVC orienté objet sans utiliser de framework.

## Étude du projet

### Utilisateurs

```puml {filename="users.png"}
@startuml

skinparam monochrome true
skinparam shadowing false
skinparam handwritten true
skinparam defaultFontName Comic Sans MS

skinparam linetype polyline
skinparam packageBackgroundColor #DDDDDD

left to right direction

actor Visiteur
actor Editeur

rectangle Blog #DDDDDD

Visiteur -- Blog
Editeur -- Blog

@enduml
```

### Interfaces

```puml {filename="interfaces.png"}
@startuml
	skinparam monochrome true
	skinparam shadowing false
	skinparam handwritten true
	skinparam defaultFontName Comic Sans MS

	left to right direction
	skinparam packageStyle rectangle
	skinparam packageBackgroundColor #DDDDDD

	actor Visiteur
	actor Editeur
	actor DB <<system>>

	rectangle Blog {
		Visiteur -- [Interface_Visiteur]
		Editeur -- [Interface_Administrateur]
		(Interface_Visiteur) -- DB
		(Interface_Administrateur) -- DB
	}
@enduml
```

### Cas d'utilisation

```puml {filename="usage.png"}
@startuml
	skinparam monochrome true
	skinparam shadowing false
	skinparam handwritten true
	skinparam defaultFontName Comic Sans MS
	skinparam linetype polyline
	skinparam packageBackgroundColor #DDDDDD
	left to right direction
	skinparam packageStyle rectangle
	actor Visiteur
	rectangle Interface_Visiteur {
		Visiteur -- (Consulter liste des articles Publié)
		Visiteur -- (Lire un article publié)
		Visiteur -- (Lire les commentaires)
		Visiteur -- (Écrire un commentaire)
		Visiteur -- (Signaler un commentaire)
	}
	actor Editeur
	rectangle Interface_Administrateur {
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

### Classes
```puml {filename="classes.png"}
@startuml
	skinparam monochrome true
	skinparam shadowing false
	skinparam handwritten true
	skinparam defaultFontName Comic Sans MS
	set namespaceSeparator ::
	skinparam packageStyle rectangle
	skinparam packageBackgroundColor #DDDDDD
	skinparam linetype ortho

	class     DBManager
	class     View
	class     Router
	class     Blog::Comment
	class     Blog::CommentManager
	class     Blog::Controller
	interface Blog::UserBlog
	class     Blog::Post
	class     Blog::PostManager
	class     User::Controller
	class     User::User
	class     User::Guest
	class     User::Admin
	class     User::Member
	class     User::UserManager

	User::User              <|-- User::Guest
	User::User              <|-- User::Admin
	User::User              <|-- User::Member
	Blog::UserBlog         <|. User::User
	Router               "1" -- "1" Blog::Controller : use >
	Router               "1" -- "1" User::Controller : use >
	User::Controller     "1" -- "1" View : use >
	Blog::Controller     "1" -- "1" View : use >
	User::UserManager    "1" -- "1" DBManager : use >
	User::UserManager    "1" -- "*" User::User : manage >
	User::Controller     "1" -- "1" User::UserManager : manage >
	Blog::Controller     "1" -- "1" Blog::CommentManager : use >
	Blog::Controller     "1" -- "1" Blog::PostManager : use >
	Blog::CommentManager "1" -- "1" Blog::UserBlog : use >
	Blog::PostManager    "1" -- "1" Blog::UserBlog : use >
	Blog::CommentManager "1" -- "1" DBManager : use >
	Blog::CommentManager "1" -- "*" Blog::Comment : manage >
	Blog::PostManager    "1" -- "1" DBManager : use >
	Blog::PostManager    "1" -- "*" Blog::Post : manage >
@enduml
```
```puml {filename="classes_detail.png"}
@startuml
	skinparam monochrome true
	skinparam shadowing false
	skinparam handwritten true
	skinparam defaultFontName Comic Sans MS
	set namespaceSeparator ::
	skinparam packageStyle rectangle
	skinparam packageBackgroundColor #DDDDDD
	skinparam linetype ortho

	class     DBManager {
		#db
		__construct(db)
		}
	class     View {
		+ __get(name)
		+ __set(name, value)
		+ __isset(name)
		+ __unset(name)
		}
	class     Router {
		+ __construct(url)
		+ get(path, function)
		+ post(path, function)
		+ all(path, function)
		+ default(path, function)
		+ run(method, path, function)
		+ match(path)
		}
	class     Blog::Comment {
		#id
		#post_id
		#author_id
		#content
		#post_date
		#visibility
		}
	class     Blog::CommentManager {
		#db
		+ __construct(user: User): bool
		+ set_user(user: User): bool
		.. CRUD ..
		+ create(data: array): bool
		+ read(id: int): Comment
		+ update(id: int, data: array): bool
		+ delete(id: int): bool
		ddd
		.. comment ..
		+ list(): array[Comment]
		+ report(id: int): bool
		+ moderate(id: int, status: bool): bool
		}
	class     Blog::Controller {
		}
	interface Blog::UserBlog {
		{static} #NONE = 0
		{static} #PUBLIC = 1
		{static} #PRIVATE = 2
		{static} #SELF = 4
		{static} #OTHER = 8
		{static} #ALL = 15
		.. Post..
		# post_can_create: NONE
		# post_can_read: PUBLIC
		# post_can_update: SELF
		# post_can_delete: SELF
		# post_can_publish: NONE
		.. Comment..
		# comment_can_create: ALL
		# comment_can_read: PUBLIC | SELF
		# comment_can_update: SELF
		# comment_can_delete: SELF
		# comment_can_report: ALL
		}
	class     Blog::Post {
		#id
		#title
		#content
		#author_id
		#post_date
		#visibility
		}
	class     Blog::PostManager {
		+ get_comments(id): Comment
		.. CRUD ..
		+ create(data: array): bool
		+ read(id: int): Post
		+ update(id: int): bool
		+ delete(id: int, data: array): bool
		.. LIST ..
		+ list(): array[Post]
		}
	class     User::Controller
	class     User::User {
		#type
		#id
		#name
		}
	class     User::Guest {
		.. Post..
		# post_can_create: NONE
		# post_can_read: PUBLIC
		# post_can_update: NONE
		# post_can_delete: NONE
		# post_can_publish: NONE
		.. Comment..
		# comment_can_create: ALL
		# comment_can_read: PUBLIC
		# comment_can_update: NONE
		# comment_can_delete: NONE
		# comment_can_report: ALL
		}
	class     User::Admin {
		.. Post..
		# post_can_create: ALL
		# post_can_read: ALL
		# post_can_update: ALL
		# post_can_delete: ALL
		# post_can_publish: ALL
		.. Comment..
		# comment_can_create: ALL
		# comment_can_read: ALL
		# comment_can_update: ALL
		# comment_can_delete: ALL
		# comment_can_report: ALL
		}
	class     User::Member {
		.. Post..
		# post_can_create: NONE
		# post_can_read: PUBLIC | SELF
		# post_can_update: SELF
		# post_can_delete: NONE
		# post_can_publish: NONE
		.. Comment..
		# comment_can_create: ALL
		# comment_can_read: PUBLIC | SELF
		# comment_can_update: SELF
		# comment_can_delete: SELF
		# comment_can_report: ALL
		}
	class     User::UserManager {
		+add(): User
		+connect(): User
		+disconnect(): User
		}

	User::User              <|-- User::Guest
	User::User              <|-- User::Admin
	User::User              <|-- User::Member
	Blog::UserBlog         <|. User::User
	Router               "1" -- "1" Blog::Controller : use >
	Router               "1" -- "1" User::Controller : use >
	User::Controller     "1" -- "1" View : use >
	Blog::Controller     "1" -- "1" View : use >
	User::UserManager    "1" -- "1" DBManager : use >
	User::UserManager    "1" -- "*" User::User : manage >
	User::Controller     "1" -- "1" User::UserManager : manage >
	Blog::Controller     "1" -- "1" Blog::CommentManager : use >
	Blog::Controller     "1" -- "1" Blog::PostManager : use >
	Blog::CommentManager "1" -- "1" Blog::UserBlog : use >
	Blog::PostManager    "1" -- "1" Blog::UserBlog : use >
	Blog::CommentManager "1" -- "1" DBManager : use >
	Blog::CommentManager "1" -- "*" Blog::Comment : manage >
	Blog::PostManager    "1" -- "1" DBManager : use >
	Blog::PostManager    "1" -- "*" Blog::Post : manage >
@enduml
```

### Base de données

```puml {filename="db.png"}
@startuml
	skinparam monochrome true
	skinparam shadowing false
	skinparam handwritten true
	skinparam defaultFontName Comic Sans MS
	set namespaceSeparator ::
	skinparam packageStyle rectangle
	skinparam packageBackgroundColor #DDDDDD
	skinparam linetype ortho

	!define Table(x) entity x << (T,#FFAAAA) >>
	!define primary_key(x) <u>x</u>
	hide methods
	hide stereotypes

	Table(Users) {
		type ENUM
		primary_key(id) INT
		name VARCHAR[32]
		emailhash VARCHAR[255]
		passwordhash VARCHAR[255]
	}

	Table(Comments) {
		+primary_key(id) INTEGER
		+post_id INT
		+author INT
		+content TEXT
		+post_date DATETIME
		+visibility ENUM("hidden","public")
	}

	Table(Posts) {
		+primary_key(id) INTEGER
		+post_id INT
		+author INT
		+title TEXT
		+content TEXT
		+post_date DATETIME
		+visibility ENUM("hidden","public")
	}

	Users "id" --> "author" Posts : ""
	Users "id" --> "author" Comments : ""
	Comments "post_id" --> "id" Posts : ""
@enduml
```

## Documentation
### Structure
Le projet utilise la hierarchie suivante

- **cfg** : contient la configuration
- **mod** : contient les modules (partie exécutive)
  - **Router** : coordonne les actions
  - **Controller** : traite les actions
  - **Manager** : traite les données
- **dat** : contient les données (nécessite vérification ou modification)
  - **vue** : contient les vues
- **res** : contient les ressources (servis en l'état)
