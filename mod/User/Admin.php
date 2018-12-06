<?php
namespace User;

class Admin extends User
{
    public function __construct($data=[])
    {
        parent::__construct($data);
        $this->post_can_create    = self::ALL;
        $this->post_can_read      = self::ALL;
        $this->post_can_update    = self::ALL;
        $this->post_can_delete    = self::ALL;
        $this->post_can_publish   = self::ALL;

        $this->comment_can_create = self::ALL;
        $this->comment_can_read   = self::ALL;
        $this->comment_can_update = self::ALL;
        $this->comment_can_delete = self::ALL;
        $this->comment_can_report = self::ALL;
    }
}
