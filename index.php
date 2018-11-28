<?php
require('mod/ClassAutoload.php');
ClassAutoload::register();
$router = new Router($_GET["url"]);
$router->all('/admin', function(){
    echo "Page de connexion";
});
$router->all('/posts/:id', function($id){
    echo "Voila l'article $id";
});
$default = function(){
    echo "Voila la liste d'articles";
};
$router->all('/posts', $default);
$router->default($default);
