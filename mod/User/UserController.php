<?php

namespace User;

use \User\User;
use \User\Model\UserManager;
use \User\Model\Admin;
use \User\Model\Guest;
use \User\Model\Member;

class UserController
{
    private $user;
    private $userManager;

    public function __construct(UserUserI $as)
    {
        $this->user = $as;
        $this->userManager = new UserManager($as);
    }
    public function ask()
    {
        global $view;
        if ($view->user->type != "Guest") {
            return false;
        }
        $view->content = include "dat/view/UserLogin.phtml";
    }
    public function login($post)
    {
        global $view;
        try {
            $emailhash = crypt($post["email"], "_J9..rasm");
            $passwordhash = crypt($post["email"]." ".$post["password"], "_J9..rasm");

            $_SESSION["user"] = $view->user = $this->userManager->login($emailhash, $passwordhash);

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
        $view->message .= '<div class="success"><div class="fixer">Déconnexion !</div></div>';
        return false;
    }
    public function create($post)
    {
        global $view;
        try {
            $name = nl2br(htmlspecialchars((string) $post['name'] ?: ""));

            if (!filter_var($post["email"], FILTER_VALIDATE_EMAIL) || empty($post["name"]) || empty($post["password"])) {
                throw new \Exception("Informations invalides !");
            }

            $emailhash = crypt($post["email"], "_J9..rasm");
            $passwordhash = crypt($post["email"]." ".$post["password"], "_J9..rasm");

            $userManager = new UserManager($this->user);
            $_SESSION["user"] = $view->user = $this->userManager->create($name, $emailhash, $passwordhash);

            $view->message .= '<div class="success"><div class="fixer">Création du compte '.$view->user->name.'</div></div>';
        } catch (\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
        }
        return false;
    }
    public function remember($post)
    {
    }
}
