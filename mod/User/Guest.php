<?php
namespace User;

class Guest extends User
{
    protected $post_can_create      = self::NONE;
    protected $post_can_read        = self::PUBLIC;
    protected $post_can_update      = self::NONE;
    protected $post_can_delete      = self::NONE;
    protected $post_can_publish     = self::NONE;

    protected $comment_can_create   = self::NONE;
    protected $comment_can_read     = self::PUBLIC;
    protected $comment_can_update   = self::NONE;
    protected $comment_can_delete   = self::NONE;
    protected $comment_can_report   = self::NONE;
    protected $comment_can_unreport = self::NONE;
}
