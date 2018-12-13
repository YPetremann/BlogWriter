<?php
require('mod/ClassAutoload.php');

ClassAutoload::register();
session_start();

$_SESSION["user"] = $_SESSION["user"] ?? new User\Guest();

$router = new Router($_GET["url"]);
$view = new View();
$view->user = $_SESSION["user"];
$view->url = new Path(":path");
$view->uniqueurl = new Path(":path", true);
$errorPage = function () {
    global $view;
    $view->content = include "dat/view/MainError.phtml";
};
try {
    $BlogC                 = new Blog\Controller($_SESSION["user"]);
    $UserC                 = new User\Controller($_SESSION["user"]);

    // Manage user
    $view->urlUserLoginPOST        = $router->post('/user/login',                      function()   {global $UserC; return $UserC->login($_POST);});
    $view->urlUserCreatePOST       = $router->post('/user/create',                     function()   {global $UserC; return $UserC->create($_POST);});
    $view->urlUserForgetPOST       = $router->post('/user/remember',                   function()   {global $UserC; return $UserC->remember($_POST);});
    $view->urlUserLogin            = $view->urlUserLoginPOST;
                                     $router->all ('/user/...',                        function()   {global $UserC; return $UserC->ask($_POST);});
    $view->urlUserLogout           = $router->all ('/user/logout',                     function()   {global $UserC; return $UserC->logout();});

    // Manage comments in comment view
    $view->urlCommentDelete        = $router->all ('/comment/:id/delete',              function($id){global $BlogC; return $BlogC->deleteComment($id);});
    $view->urlCommentReport        = $router->all ('/comment/:id/report',              function($id){global $BlogC; return $BlogC->reportComment($id);});
    $view->urlCommentUnreport      = $router->all ('/comment/:id/unreport',            function($id){global $BlogC; return $BlogC->unreportComment($id);});
    $view->urlCommentPublish       = $router->all ('/comment/:id/publish',             function($id){global $BlogC; return $BlogC->publishComment($id);});
    $view->urlCommentUnpublish     = $router->all ('/comment/:id/unpublish',           function($id){global $BlogC; return $BlogC->unpublishComment($id);});
    $view->urlCommentList          = $router->all ('/comment/list',                    function()   {global $BlogC; return $BlogC->listComment();});
                                     $router->all ('/comment/...',                     function()   {global $BlogC; return $BlogC->listComment();});

    // Manage comment in post view
    $view->urlPostCommentCreate    = $router->post('/post/:id/comment/create',         function($id){global $BlogC; return $BlogC->createComment($id, $_POST);});
    $view->urlPostCommentUpdate    = $router->all ('/post/:-id/comment/:id/update',    function($id){global $BlogC; return $BlogC->updateComment($id, $_POST);});
    $view->urlPostCommentDelete    = $router->all ('/post/:-id/comment/:id/delete',    function($id){global $BlogC; return $BlogC->deleteComment($id);});
    $view->urlPostCommentReport    = $router->all ('/post/:-id/comment/:id/report',    function($id){global $BlogC; return $BlogC->reportComment($id);});
    $view->urlPostCommentUnreport  = $router->all ('/post/:-id/comment/:id/unreport',  function($id){global $BlogC; return $BlogC->unreportComment($id);});
    $view->urlPostCommentPublish   = $router->all ('/post/:-id/comment/:id/publish',   function($id){global $BlogC; return $BlogC->publishComment($id);});
    $view->urlPostCommentUnpublish = $router->all ('/post/:-id/comment/:id/unpublish', function($id){global $BlogC; return $BlogC->unpublishComment($id);});

    // Manage post editing
    $view->urlPostCreatePOST       = $router->post('/post/create',                     function()   {global $BlogC, $router; $id=$BlogC->createPost($_POST); $router->method('GET'); $router->url('/post/'.$id.'/update');return false;});
    // Manage post
    $view->urlPostCreate           = $router->all ('/post/create',                     function()   {global $BlogC, $router; $router->url('/post/list'); return $BlogC->editPost($post_id);});
    $view->urlPostPublish          = $router->all ('/post/:id/publish',                function($id){global $BlogC; return $BlogC->publishPost($id);});
    $view->urlPostUnpublish        = $router->all ('/post/:id/unpublish',              function($id){global $BlogC; return $BlogC->unpublishPost($id); });
    $view->urlPostPublish          = $router->post('/post/:id/publish',                function($id){global $BlogC; return $BlogC->updatePost($id,$_POST);});
    $view->urlPostUnpublish        = $router->post('/post/:id/unpublish',              function($id){global $BlogC; return $BlogC->updatePost($id,$_POST);});
    $view->urlPostUpdatePOST       = $router->post('/post/:id/update',                 function($id){global $BlogC; return $BlogC->updatePost($id,$_POST);});
    $view->urlPostUpdate           = $router->all ('/post/:id/update',                 function($id){global $BlogC; return $BlogC->editPost($id);});
    $view->urlPostDelete           = $router->all ('/post/:id/delete',                 function($id){global $BlogC; return $BlogC->deletePost($id);});
    $view->urlPostRead             = $router->all ('/post/:id/read',                   function($id){global $BlogC; return $BlogC->readPost($id);});
                                     $router->all ('/post/:id/...',                    function($id){global $BlogC; return $BlogC->readPost($id);});
    $view->urlPostList             = $router->all ('/post/list',                       function()   {global $BlogC; return $BlogC->listPost();});
    //default view
    $router->default(                                                                  function()   {global $BlogC; return $BlogC->listPost();});
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
