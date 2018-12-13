<?php
namespace User;

class Guest extends User
{
    protected $post_can_read        = self::PUBLIC | self::SELF;
    protected $comment_can_create   = self::PUBLIC;
    protected $comment_can_read     = self::PUBLIC;
    protected $comment_can_report   = self::OTHER;
}
