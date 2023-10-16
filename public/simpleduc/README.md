# Simpléduc

Il s'agit d'un site développé en PHP, Twig et Bootstrap.

## Sommaire

-  [Architecture](#architecture)
	- [Bootstrap](#bootstrap)
	- [PHP](#php)
		- [App](#app)
		- [Config](#config)
		- [Controller](#controller)
		- [Modele](#modele)
		- [View](#view)
		- [Specifications](#specifications)

## Architecture

#### Bootstrap

- Nous utilisons Bootstrap dans ce projet. Il s'agit d'une infrastructure logicielle qui délivre une librairie HTML, CSS et JS.

- Bootstrap se trouve dans les dossiers:
-- 📁 `css` qui contient le fichier *index.css*. Ce fichier contient toutes les règles css pour le site.
-- 📁 `js` qui possède le fichier *main.js*. Ce fichier contient toutes les fonctions javascript pour le site.
- Nous avons ajouté le fichier *index.css* pour le design et le fichier *main.js* qui comporte toutes les fonctions javascript.

#### PHP

- L'architecture principale de PHP se trouve dans le dossier 📁`src` :
-- Il contient une architecture MVC (modèle - vue - controlleur).
-- Le dossier 📁`lib` comporte Twig qui sert à modeler le code PHP.

- Ici se trouve l'architecture de base du dossier 📁`src` :
```
.
├── 📁 src
│ ├── 📁 app
│ │ └── connexion.php
│ ├── 📁 config
| | ├── parametres.php
│ │ └── routing.php
│ ├── 📁 controller
│ | ├── _controller.php
| | ├── controller_employee.php
| | └── ...
│ ├── 📁 lib
| ├── 📁 model
│ | ├── _classes.php
| | ├── class_employee.php
| | └── ...
| ├── 📁 view
│ | ├── base.html.twig
| | ├── index.html.twig
| | ├── employee_list.html.twig
| | └── ...
```

- Le dossier 📁`specifications` est l'endroit où tous les PDF sont envoyés lors de la soumission du formulaire. Sur ce
	serveur, le lien complet est :

`/var/www/html/symfony4-4020/public/simpleduc/src/specifications/`

##### 📁 App

Ce dossier possède le fichier `connexion.php` permettant la connexion à la base de données.

##### 📁 Config

Ce dossier possède le fichier `parametres.php` comprenant l'adresse du serveur, l'identifiant, le mot de passe et le nom de la base de données requis par le fichier `connexion.php`.

Le fichier `routing.php` comprend chaque route utilisée par le site, ainsi que la gestion de la connexion des utilisateurs sur le site. Si la connexion à la base de données échoue, une page sera affichée pour montrer que le site est en maintenance.

##### 📁 Controller

`_controller.php` fait apppel à tous les contrôleurs présents dans le dossier;

`controller_index.php` gère l'affichage de la page d'accueil, de la page 'A propos', de la page 'Mentions légales';

`controller_user.php` gère l'action d'inscription, de connexion, de déconnexion, d'affichage et de modification des utilisateurs;

`controller_webservices.php` comprend toutes les fonctions faisant appel à un service web, ici la génération de PDF;

Tous les autres fichiers servent à afficher des listes, les modifier et les supprimer suivant chaque table.

##### 📁 Modele
`_classes.php` fait appel à toutes les classes présentes dans le dossier;
Chaque fichier comporte des requêtes SQL.
Pour chaque requête, nous créons une fonction qui lie les données reçue à une méthode. Ces mêmes méthodes sont utilisées dans les contrôleurs.

##### 📁 View
Le fichier `base.html.twig` est la base du site.
Tous les autres contenus affichés dans les pages sont placés entre les balises Twig `{% block contenu %}` et `{% endblock %}`
Pour chaque élément nécessaire, nous créons les fichiers add[NOUVEL_ELEMENT].html.twig, change[NOUVEL_ELEMENT].html.twig, list[NOUVEL_ELEMENT].html.twig, pdf[NOUVEL_ELEMENT].html.twig. Ils contiennent des formulaires pour mettre à jour un élément, en ajouter de nouveau. Ils permettent également de visualiser et de télécharger au format PDF.