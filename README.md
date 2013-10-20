Bird
====

Système de routing PHP

Installer Bird

Après avoir téléchargé Bird, il vous suffit d'insérer dans votre projet le fichier Bird.php, le fichier .htaccess (à la racine de votre projet) et d'utiliser le code suivant. 

Remarque : N'oubliez pas d'activer le mod_rewrite dans Apache !

<?php
require('Hermes.php');
$hermes = new Hermes();
?>
