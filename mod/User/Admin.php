<?php
namespace User;

class Admin extends User
{
    protected $post_can_create      = self::ALL;
    protected $post_can_read        = self::ALL;
    protected $post_can_update      = self::ALL;
    protected $post_can_delete      = self::ALL;
    protected $post_can_publish     = self::ALL;
    protected $post_can_unpublish   = self::ALL;

    protected $comment_can_create   = self::ALL;
    protected $comment_can_read     = self::ALL;
    protected $comment_can_update   = self::ALL;
    protected $comment_can_delete   = self::ALL;
    protected $comment_can_report   = self::ALL;
    protected $comment_can_unreport = self::ALL;
    protected $comment_can_publish   = self::ALL;
    protected $comment_can_unpublish = self::ALL;
}
