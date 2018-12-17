<?php
namespace User;

class Guest extends User
{
    protected $user_can_login       = TRUE;
    protected $user_can_logout      = FALSE;

    protected $post_can_read        = self::PUBLIC | self::SELF;
    protected $comment_can_create   = self::PUBLIC;
    protected $comment_can_read     = self::PUBLIC;
    protected $comment_can_report   = self::OTHER;
}
