<?php
namespace Blog;

interface UserBlogI
{
    const NONE = 0;
    const PUBLIC = 1;
    const PRIVATE = 2;
    const SELF = 4;
    const OTHER = 8;
    const ALL = 15;
}
trait UserBlogT
{
    protected $post_can_create      = UserBlogI::NONE;
    protected $post_can_read        = UserBlogI::PUBLIC;
    protected $post_can_update      = UserBlogI::NONE;
    protected $post_can_delete      = UserBlogI::NONE;
    protected $post_can_publish     = UserBlogI::NONE;

    protected $comment_can_create   = UserBlogI::ALL;
    protected $comment_can_read     = UserBlogI::PUBLIC;
    protected $comment_can_update   = UserBlogI::NONE;
    protected $comment_can_delete   = UserBlogI::NONE;
    protected $comment_can_report   = UserBlogI::NONE;
    protected $comment_can_unreport = UserBlogI::NONE;

    public function get_post_can_create()      { return $this->post_can_create; }
    public function get_post_can_read()        { return $this->post_can_read; }
    public function get_post_can_update()      { return $this->post_can_update; }
    public function get_post_can_delete()      { return $this->post_can_delete; }
    public function get_post_can_publish()     { return $this->post_can_publish; }
    public function get_post_can_unpublish()   { return $this->post_can_unpublish; }

    public function get_comment_can_create()   { return $this->comment_can_create; }
    public function get_comment_can_read()     { return $this->comment_can_read; }
    public function get_comment_can_update()   { return $this->comment_can_update; }
    public function get_comment_can_delete()   { return $this->comment_can_delete; }
    public function get_comment_can_report()   { return $this->comment_can_report; }
    public function get_comment_can_unreport() { return $this->comment_can_unreport; }
}
