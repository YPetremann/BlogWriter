<?php

namespace User;
class Controller {
    public function __construct($as){
        $this->user = $as;
    }
    public function ask(){
        global $view;
        $view->content = include "dat/view/UserLogin.phtml";
    }
    public function login($post){
        global $view;
        try {
            $emailhash = crypt($post["email"],"_J9..rasm");
            $passwordhash = crypt($post["email"]." ".$post["password"],"_J9..rasm");
            $userManager = new UserManager($this->user);
            $view->user = $userManager->login($emailhash,$passwordhash);
            $_SESSION["user"] = $view->user;

            $view->message .= '<div class="success"><div class="fixer">Connexion en tant que '.$view->user->name.'</div></div>';
        } catch(\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
            return false;
        }
    }
    public function logout(){
        global $view;
        $view->user = new Guest();
        $_SESSION["user"] = $view->user;
    }
    public function remember(){
    }
    public function password(){
    }
}
