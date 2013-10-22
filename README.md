Bird
====

Bird se veut être un simple système de routing sans aucune dépendance. Il vous suffit de charger la classe et ... de l'utiliser.

Installer Bird
====

Après avoir téléchargé Bird, il vous suffit d'insérer dans votre projet le fichier bird.php, le fichier .htaccess (à la racine de votre projet) et d'utiliser le code suivant. 

````php
<?php
require('bird.php');
$bird = new Bird();
?>
````

Créer une "route" de type Get
====

Le code ci-dessous vous montre quelques exemples d'utilisation de la classe pour les requêtes de type GET.

````php
<?php
require('bird.php');
$bird = new Bird();

$bird->get('/hi', function () {
	echo 'hi guy !';
});

$bird->get('/author', function () {
	echo 'jeremie ges';
});

$bird->get('/a/fat/pattern', function () {
	echo 'Yes, i see';
});
?>
````

Créer une "route" de type Post
====

Le code ci-dessous aura le même effet que celui vu juste avant, la différence est que Bird n'acceptera que des requêtes de type POST sur les différents pattern alloués.

````php
<?php

$bird->post('/hi', function () {
	echo 'hi guy !';
});


?>
````

Créer une "route" ne tenant pas compte du type de requête
====

````php
$bird->any('/hello', function() {
	echo 'Hello world';
});
````

Passer des variables dans le pattern
====

`````php
<?php
require('Bird.php');
$bird = new Bird();

$bird->get('/view/user/:id', function ($id) {
	echo 'You want see user with id = ' . $id;
});
?>

````

Callbacks
====

Vous pouvez passer en deuxième paramètre des routes tout ce que peut accepter call_user_func_array(), ainsi il vous est possible de faire quelque chose dans ce goût là :

````php
<?php

require('bird.php');

$bird = new Bird();

class Callback {

	public function __construct() {

	}

	public function test() {

		echo 'Hello world !';

	}

}

$callback = new Callback();

$bird->any('/hello', array(&$callback, 'test'));

?>
````

Erreur 404
====

````php
<?php
/**
 * Bird n'a pas "routé" un seul pattern
 */
if ($bird->is_404()) {
	echo '404 not found';
}

?>
````
