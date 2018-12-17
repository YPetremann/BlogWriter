<?php
namespace User;

interface UserUserI
{
}
trait UserUserT
{
    protected $user_can_login        = FALSE;
    protected $user_can_logout       = TRUE;

    public function get_user_can_login()        { return $this->user_can_login;  }
    public function get_user_can_logout()       { return $this->user_can_logout; }
}
