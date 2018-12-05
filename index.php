<?php
require('mod/ClassAutoload.php');
ClassAutoload::register();
session_start();
$_SESSION["user"] = $_SESSION["user"] ?? new User\Guest();

function url($url, $unique=false)
{
    $prefix = "/BlogWriter";
    $url = $prefix . $url;
    (!$unique) ?: $url .= "?v=".uniqid('', true);
    return $url;
}

$router = new Router($_GET["url"]);
$view = new View();
$view->header = include "dat/view/header.phtml";
$view->footer = include "dat/view/footer.phtml";

// Login page, Display login form
$router->all('/login', function () {
    global $view;
    $blog = new Blog\Controller();
    $view->content = include "dat/view/UserLogin.phtml";
});

// Edit post page, Permit to edit post
$router->post('/posts/:id/edit', function ($id) {
    global $view;
    if($_SESSION["user"]->post_can_create)
    $blog = new Blog\Controller();
    $post = $blog->readPost($id);

    $view->content = include "dat/view/BlogPostEdit.phtml";
});

$router->all('/posts/:id/edit', function ($id) {
    global $view;
    if($_SESSION["user"]->post_can_create)
    $blog = new Blog\Controller();
    $post = $blog->readPost($id);

    $view->content = include "dat/view/BlogPostEdit.phtml";
});

// Post page, Display post and comments
$router->post('/posts/:id', function ($id) {
    global $view;

    $blog = new Blog\Controller();
    $post = $blog->createComment($id, $_POST);
    $post = $blog->readPost($id);

    $view->content = include "dat/view/BlogPost.phtml";
});
$router->all('/posts/:id', function ($id) {
    # TODO gerer cas ou router
    global $view;

    $blog = new Blog\Controller();
    $post = $blog->readPost($id);

    $view->content = include "dat/view/BlogPost.phtml";
});

// Home page, display blog post list
$default = function () {
    global $view;

    $blog = new Blog\Controller();
    $posts = $blog->listPost();

    $view->content = include "dat/view/BlogList.phtml";
};

$router->all('/posts', $default);
$router->default($default);

echo include "dat/view/main.phtml";
