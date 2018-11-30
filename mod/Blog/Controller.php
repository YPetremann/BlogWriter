<?php
namespace Blog;
class Controller {
    public function lists(){
        return [
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
    }
    public function post($id) {
        return [
            "title"=>"Chapitre 1",
            "post_date_fr"=>"20/11/2018",
            "content"=>"Contenu du blog",
            "id"=>1,
            "comments" => [
                [
                    "id"=>1,
                    "author" => "Anon",
                    "comment_date_fr"=>"20/11/2018",
                    "content"=>"Voici du contenu",
                ],
                [
                    "id"=>1,
                    "author" => "Anon",
                    "comment_date_fr"=>"20/11/2018",
                    "content"=>"Voici du contenu",
                ],
                [
                    "id"=>1,
                    "author" => "Anon",
                    "comment_date_fr"=>"20/11/2018",
                    "content"=>"Voici du contenu",
                ],
                [
                    "id"=>1,
                    "author" => "Anon",
                    "comment_date_fr"=>"20/11/2018",
                    "content"=>"Voici du contenu",
                ],
                [
                    "id"=>1,
                    "author" => "Anon",
                    "comment_date_fr"=>"20/11/2018",
                    "content"=>"Voici du contenu",
                ]
            ]
        ];
    }
}
