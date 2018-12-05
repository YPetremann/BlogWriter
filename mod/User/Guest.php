<?php
namespace User;

class Guest extends User
{
    public function __construct()
    {
        $this->post_can_create    = self::NONE;
        $this->post_can_read      = self::PUBLIC;
        $this->post_can_update    = self::NONE;
        $this->post_can_delete    = self::NONE;
        $this->post_can_publish   = self::NONE;

        $this->comment_can_create = self::ALL;
        $this->comment_can_read   = self::PUBLIC;
        $this->comment_can_update = self::NONE;
        $this->comment_can_delete = self::NONE;
        $this->comment_can_report = self::ALL;
    }
}
