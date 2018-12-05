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
$router->post('/login', function () {
    $blog = new User\Controller($_SESSION["user"]);
    return $blog->login($_POST);
});
$router->all('/login', function () {
    $blog = new User\Controller($_SESSION["user"]);
    return $blog->login();
});

// Logout, Display logout message
$router->all('/logout', function () {
    $blog = new User\Controller($_SESSION["user"]);
    return $blog->logout();
});

// Create post page, Permit to edit post
$router->post('/posts/create', function ($id) {
    $blog = new Blog\Controller($_SESSION["user"]);
    return $blog->createPost($_POST);
});
$router->all('/posts/create', function ($id) {
    $blog = new Blog\Controller($_SESSION["user"]);
    return $blog->createPost();
});

// Edit post page, Permit to edit post
$router->post('/posts/update/:id', function ($id) {
    $blog = new Blog\Controller($_SESSION["user"]);
    return $blog->editPost($id, $_POST);
});
$router->all('/posts/update/:id', function ($id) {
    $blog = new Blog\Controller($_SESSION["user"]);
    return $blog->editPost($id);
});

// Post page, Display post and comments
$router->all('/posts/:post_id/report/:comment_id', function ($post_id, $comment_id) {
    global $router;
    $blog = new Blog\Controller($_SESSION["user"]);
    $blog->reportComment($post_id, $comment_id);

    $router->url('/posts/'.$post_id);
    return true;
});

// Post page, Display post and comments
$router->post('/posts/:id', function ($id) {
    $blog = new Blog\Controller($_SESSION["user"]);
    return $blog->createComment($id, $_POST);
});
$router->all('/posts/:id', function ($id) {
    $blog = new Blog\Controller($_SESSION["user"]);
    return $blog->readPost($id);
});

// Post page, Display post and comments
$router->all('/posts/delete/:id', function ($id) {
    $blog = new Blog\Controller($_SESSION["user"]);
    return $blog->deletePost($id);
});

// Home page, display blog post list
$default = function () {
    $blog = new Blog\Controller($_SESSION["user"]);
    return $blog->listPost();
};
$router->all('/posts', $default);
$router->default($default);
$router->default(function () {
    global $view;
    $view->content = include "dat/view/MainError.phtml";
});

echo include "dat/view/main.phtml";
