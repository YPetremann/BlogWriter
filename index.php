<?php
require('mod/ClassAutoload.php');
ClassAutoload::register();
function url($url){
    return "/BlogWriter".$url;
}

$header  = include "dat/view/header.phtml";
$footer  = include "dat/view/footer.phtml";
$router = new Router($_GET["url"]);

$router->all('/login', function(){
    global $header, $footer;
    $content = include "dat/view/UserLogin.phtml";
    include "dat/view/main.phtml";
});

$router->all('/posts/:id', function($id){
    global $header, $footer;
    /* TODO only there to test view */ //*
    $post = [
        "title"=>"Chapitre 1",
        "post_date_fr"=>"20/11/2018",
        "content"=>"Contenu du blog",
        "id"=>1,
    ];
    $comments = [
        [
            "id"=>1,
            "author" => "Anon",
            "comment_date_fr"=>"20/11/2018",
            "content"=>"Voici du contenu",
        ]
    ];
    // */

    $content = include "dat/view/BlogPost.phtml";
    include "dat/view/main.phtml";});
$default = function(){
    global $header, $footer;
    /* TODO only there to test view */ //*
    $posts = [
        [
            "id"=>1,
            "title"=>"Chapitre 1",
            "post_date_fr"=>"20/11/2018",
            "content"=>"Contenu du blog",
        ],
        [
            "id"=>2,
            "title"=>"Chapitre 2",
            "post_date_fr"=>"20/11/2018",
            "content"=>"Contenu du blog",
        ]
    ];
    // */

    $content = include "dat/view/BlogList.phtml";
    include "dat/view/main.phtml";
};
$router->all('/posts', $default);
$router->default($default);
