<?php
require('mod/ClassAutoload.php');
ClassAutoload::register();
function url($url, $unique=false)
{
    $prefix = "/BlogWriter";
    $url = $prefix . $url;
    (!$unique)?: $url .= "?v=".uniqid('', true);
    return $url;
}

$header  = include "dat/view/header.phtml";
$footer  = include "dat/view/footer.phtml";
$router = new Router($_GET["url"]);

$router->all('/login', function () {
    global $header, $footer;
    $content = include "dat/view/UserLogin.phtml";
    include "dat/view/main.phtml";
});

$router->all('/posts/:id', function ($id) {
    global $header, $footer;

    $blog = new Blog\Controller();
    $post = $blog->post($id);

    $content = include "dat/view/BlogPost.phtml";
    include "dat/view/main.phtml";
});

$default = function () {
    global $header, $footer;

    $blog = new Blog\Controller();
    $posts = $blog->lists();

    $content = include "dat/view/BlogList.phtml";
    include "dat/view/main.phtml";
};

$router->all('/posts', $default);
$router->default($default);
