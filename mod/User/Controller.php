<?php

namespace User;

class Controller
{
    public function __construct($as) { $this->user = $as; }
    public function ask()
    {
        global $view;
        if($view->user->type != "Guest") return false;
        $view->content = include "dat/view/UserLogin.phtml";
    }
    public function login($post)
    {
        global $view;
        try {
            $emailhash = crypt($post["email"], "_J9..rasm");
            $passwordhash = crypt($post["email"]." ".$post["password"], "_J9..rasm");
            $userManager = new UserManager($this->user);
            $_SESSION["user"] = $view->user = $userManager->login($emailhash, $passwordhash);

            $view->message .= '<div class="success"><div class="fixer">Connexion en tant que '.$view->user->name.'</div></div>';
        } catch (\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
        }
        return false;
    }
    public function logout()
    {
        global $view;
        $view->user = $_SESSION["user"] = new Guest();
        return false;
    }
    public function remember() {}
    public function password() {}
}
