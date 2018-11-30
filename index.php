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

// Login page, Display login form
$router->all('/login', function () {
    global $header, $footer, $content;

    $content = include "dat/view/UserLogin.phtml";
});

// Post page, Display post and comments
$router->all('/posts/:id', function ($id) {
    global $header, $footer, $content;

    $blog = new Blog\Controller();
    $post = $blog->post($id);

    $content = include "dat/view/BlogPost.phtml";
});

// Home page, display blog post list
$default = function () {
    global $header, $footer, $content;

    $blog = new Blog\Controller();
    $posts = $blog->lists();

    $content = include "dat/view/BlogList.phtml";
};

$router->all('/posts', $default);
$router->default($default);
include "dat/view/main.phtml";
