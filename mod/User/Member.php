<?php
namespace User;

class Member extends User
{
    protected $post_can_create      = self::NONE;
    protected $post_can_read        = self::PUBLIC | self::SELF;
    protected $post_can_update      = self::SELF;
    protected $post_can_delete      = self::NONE;
    protected $post_can_publish     = self::NONE;

    protected $comment_can_create   = self::PUBLIC;
    protected $comment_can_read     = self::PUBLIC | self::SELF;
    protected $comment_can_update   = self::SELF;
    protected $comment_can_delete   = self::SELF;
    protected $comment_can_report   = self::OTHER;
    protected $comment_can_unreport = self::NONE;
}
