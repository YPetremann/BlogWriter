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
$view->user = $_SESSION["user"];
$view->header = include "dat/view/header.phtml";
$view->footer = include "dat/view/footer.phtml";

$errorPage = function () {
    global $view;
    $view->content = include "dat/view/MainError.phtml";
};
try{
    // User related functions
    $userLogin = function () {
        $blog = new User\Controller($_SESSION["user"]);
        return $blog->login($_POST);
    };
    $userLogout = function () {
        $blog = new User\Controller($_SESSION["user"]);
        return $blog->logout();
    };

    $router->post('/user/login', $userLogin);
    $router->all('/user/login', $userLogin);
    $router->all('/user/logout', $userLogout);

    // Blog related functions
    $postCreate = function () {
        $blog = new Blog\Controller($_SESSION["user"]);
        return $blog->createPost($_POST);
    };
    $postRead = function ($id) {
        $blog = new Blog\Controller($_SESSION["user"]);
        return $blog->readPost($id);
    };
    $postUpdate = function ($id) {
        $blog = new Blog\Controller($_SESSION["user"]);
        return $blog->updatePost($id);
    };
    $postDelete = function ($id) {
        $blog = new Blog\Controller($_SESSION["user"]);
        return $blog->deletePost($id);
    };
    $postPublish = function ($id) {
        $blog = new Blog\Controller($_SESSION["user"]);
        return $blog->publishPost($id);
    };
    $postUnpublish = function ($id) {
        $blog = new Blog\Controller($_SESSION["user"]);
        return $blog->unpublishPost($id);
    };
    $postList = function () {
        $blog = new Blog\Controller($_SESSION["user"]);
        return $blog->listPost();
    };

    $commentCreate   = function ($post_id) {
        $blog = new Blog\Controller($_SESSION["user"]);
        return $blog->createComment($id, $_POST);
    };
    $commentUpdate   = function ($post_id, $comment_id) {
        global $router;
        $blog = new Blog\Controller($_SESSION["user"]);
        $blog->updateComment($post_id, $comment_id);

        $router->url('/posts/'.$post_id);
        return true;
    };
    $commentReport   = function ($post_id, $comment_id) {
        global $router;
        $blog = new Blog\Controller($_SESSION["user"]);
        $blog->reportComment($post_id, $comment_id);

        $router->url('/posts/'.$post_id.'/read');
        return true;
    };
    $commentUnreport = function ($post_id, $comment_id) {
        global $router;
        $blog = new Blog\Controller($_SESSION["user"]);
        $blog->unreportComment($post_id, $comment_id);

        $router->url('/posts/'.$post_id.'/read');
        return true;
    };
    $commentDelete   = function ($post_id, $comment_id) {
        global $router;
        $blog = new Blog\Controller($_SESSION["user"]);
        $blog->deleteComment($post_id, $comment_id);

        $router->url('/posts/'.$post_id.'/read');
        return true;
    };

    $router->post('/posts/:id/comment/create', $commentCreate);
    $router->all('/posts/:post_id/comment/:comment_id/update', $commentUpdate);
    $router->all('/posts/:post_id/comment/:comment_id/delete', $commentDelete);
    $router->all('/posts/:post_id/comment/:comment_id/report', $commentReport);
    $router->all('/posts/:post_id/comment/:comment_id/unreport', $commentUnreport);

    $router->post('/posts/create', $postCreate);
    $router->all('/posts/create', $postCreate);
    $router->all('/posts/:id/read', $postRead);
    $router->post('/posts/:id/update', $postUpdate);
    $router->all('/posts/:id/update', $postUpdate);
    $router->all('/posts/:id/delete', $postDelete);
    $router->all('/posts/:id/publish', $postPublish);
    $router->all('/posts/:id/unpublish', $postUnpublish);
    $router->all('/posts/list', $postList);
    $router->default($postList);
    $router->default($errorPage);
} catch(Exception $e){
    $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
    $router->default($errorPage);
} catch(Error $e){
    $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
    $router->default($errorPage);
}
// general functions

echo include "dat/view/main.phtml";
