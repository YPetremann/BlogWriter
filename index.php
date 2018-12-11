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

$errorPage = function () {
    global $view;
    $view->content = include "dat/view/MainError.phtml";
};
try {
    // User related functions
    $userLogout      = function ()         { return (new User\Controller($_SESSION["user"]) )->logout(); };
    $userLogin       = function ()         { return (new User\Controller($_SESSION["user"]) )->login($_POST); };
    $userCreate      = function ()         {
        global $router; $router->url('/user/login');
        return (new User\Controller($_SESSION["user"]) )->create($_POST);
    };
    $userRemeber     = function ()         {
        global $router; $router->url('/user/login');
        return (new User\Controller($_SESSION["user"]) )->remember($_POST);
    };
    $userAsk         = function ()         { return (new User\Controller($_SESSION["user"]) )->ask($_POST); };

    // Blog related functions
    $postCreate      = function ()         {
        $post_id = (new Blog\Controller($_SESSION["user"]) )->createPost($_POST);
        global $router; $router->method('GET'); $router->url('/posts/'.$post_id.'/update');
        return false;
    };
    $postUpdate      = function ($post_id) {
        global $router; $router->method('GET'); $router->url('/posts/'.$post_id.'/update');
        return (new Blog\Controller($_SESSION["user"]) )->updatePost($post_id,$_POST);
    };
    $postEdit        = function ($post_id = null) {
        global $router; $router->url('/posts/'.$post_id.'/read');
        return (new Blog\Controller($_SESSION["user"]) )->editPost($post_id);
    };

    $postRead        = function ($post_id) { return (new Blog\Controller($_SESSION["user"]) )->readPost($post_id); };
    $postDelete      = function ($post_id) {
        return (new Blog\Controller($_SESSION["user"]) )->deletePost($post_id);
    };
    $postPublish     = function ($post_id) {
        return (new Blog\Controller($_SESSION["user"]) )->publishPost($post_id);
    };
    $postUnpublish   = function ($post_id) {
        return (new Blog\Controller($_SESSION["user"]) )->unpublishPost($post_id);
    };
    $postList        = function ()         { return (new Blog\Controller($_SESSION["user"]) )->listPost(); };

    $commentCreate   = function ($post_id) {
        global $router; $router->url('/posts/'.$post_id.'/read');
        return (new Blog\Controller($_SESSION["user"]) )->createComment($post_id, $_POST);
    };
    $commentUpdate   = function ($post_id, $comment_id) {
        global $router; $router->url('/posts/'.$post_id);
        return (new Blog\Controller($_SESSION["user"]) )->updateComment($post_id, $comment_id);
    };
    $commentReport   = function ($post_id, $comment_id) {
        global $router; $router->url('/posts/'.$post_id.'/read');
        return (new Blog\Controller($_SESSION["user"]) )->reportComment($comment_id);
    };
    $commentUnreport = function ($post_id, $comment_id) {
        global $router; $router->url('/posts/'.$post_id.'/read');
        return (new Blog\Controller($_SESSION["user"]) )->unreportComment($comment_id);
    };
    $commentPublish     = function ($post_id, $comment_id) {
        global $router; $router->url('/posts/'.$post_id.'/read');
        return (new Blog\Controller($_SESSION["user"]) )->publishComment($comment_id);
    };
    $commentUnpublish   = function ($post_id, $comment_id) {
        global $router; $router->url('/posts/'.$post_id.'/read');
        return (new Blog\Controller($_SESSION["user"]) )->unpublishComment($comment_id);
    };

    $commentDelete   = function ($post_id, $comment_id) {
        global $router; $router->url('/posts/'.$post_id.'/read');
        return (new Blog\Controller($_SESSION["user"]) )->deleteComment($post_id, $comment_id);
    };

    // User related url bindinds
    $view->urlUserLoginPOST   = $router->post('/user/login', $userLogin);
    $view->urlUserCreatePOST  = $router->post('/user/create', $userCreate);
    $view->urlUserForgetPOST  = $router->post('/user/remember', $userRemeber);
    $view->urlUserLogin       = $router->all('/user/login', $userAsk);
    $view->urlUserLogout      = $router->all('/user/logout', $userLogout);

    // Blog related url bindinds
    $view->urlCommentCreate   = $router->post('/posts/:id/comment/create', $commentCreate);
    $view->urlCommentUpdate   = $router->all('/posts/:post_id/comment/:comment_id/update', $commentUpdate);
    $view->urlCommentDelete   = $router->all('/posts/:post_id/comment/:comment_id/delete', $commentDelete);
    $view->urlCommentReport   = $router->all('/posts/:post_id/comment/:comment_id/report', $commentReport);
    $view->urlCommentUnreport = $router->all('/posts/:post_id/comment/:comment_id/unreport', $commentUnreport);
    $view->urlCommentPublish   = $router->all('/posts/:post_id/comment/:comment_id/publish', $commentPublish);
    $view->urlCommentUnpublish = $router->all('/posts/:post_id/comment/:comment_id/unpublish', $commentUnpublish);

    $view->urlPostCreatePOST  = $router->post('/posts/create', $postCreate);
    $view->urlPostCreate      = $router->all('/posts/create', $postEdit);
    $view->urlPostPublish     = $router->all('/posts/:id/publish', $postPublish);
    $view->urlPostUnpublish   = $router->all('/posts/:id/unpublish', $postUnpublish);
    $view->urlPostPublish     = $router->post('/posts/:id/publish', $postUpdate);
    $view->urlPostUnpublish   = $router->post('/posts/:id/unpublish', $postUpdate);
    $view->urlPostUpdatePOST  = $router->post('/posts/:id/update', $postUpdate);
    $view->urlPostUpdate      = $router->all('/posts/:id/update', $postEdit);
    $view->urlPostDelete      = $router->all('/posts/:id/delete', $postDelete);
    $view->urlPostRead        = $router->all('/posts/:id/read', $postRead);
    $view->urlPostList        = $router->all('/posts/list', $postList);
    $router->default($postList);
    $router->default($errorPage);
} catch (Exception $e) {
    $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
    $router->default($errorPage);
}
$router->process();
$view->header = include "dat/view/header.phtml";
$view->footer = include "dat/view/footer.phtml";
/*

catch(Error $e){
    $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
    $router->default($errorPage);
}
*/
// general functions

echo include "dat/view/main.phtml";
