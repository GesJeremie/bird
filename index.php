<?php
/*
| -------------------------------------------------------------------------
| Examples of use
| -------------------------------------------------------------------------
*/

/*
| -------------------------------------------------------------------------
| Include and init the awesome
| -------------------------------------------------------------------------
*/
require('bird.php');

$bird = new Bird;

/*
| -------------------------------------------------------------------------
| Your routing rules
| -------------------------------------------------------------------------
*/
$bird->get('/', function() {

	echo 'Hey dude !';

});

$bird->get('/author', function() {

	echo 'Jeremie Ges';

});

$bird->get('/a/fat/pattern', function() {

	echo 'Yes i see';

});


$bird->get('/view/user/:id', function($id) {

	echo 'You want see user with id = ' . $id;

});


if ($bird->is_404()) {

	echo '404 not found';

}

?>