<?php
namespace Blog;

use \DBManager;

class PostManager extends DBManager
{
    public function __construct($as){
        $this->db = DBManager::get();
        $this->user = $as;
    }
    public function get_comments($id)
    {
    }
    public function create($data)
    {
    }
    public function read($id)
    {
        $query = $this->db->prepare('SELECT
                                 p.id id,
                                 p.title title,
                                 p.content content,
                                 p.post_date post_date,
                                 u.name author
                               FROM posts p
                               INNER JOIN users u
                               ON p.author_id = u.id
                               WHERE p.id = ?');
        $query->execute([$id]);
        $data = $query->fetch();
        $query->closeCursor();
        return $data;
    }
    public function update($id)
    {
    }
    public function delete($id)
    {
    }
    public function list()
    {
        $cond = [];
        if ( $this->user->post_can_read & (UserBlogI::PUBLIC)){ array_push($cond, "p.visibility = 1"); }
        if ( $this->user->post_can_read & (UserBlogI::PRIVATE)){ array_push($cond, "p.visibility = 0"); }
        if ( $this->user->post_can_read & (UserBlogI::SELF)){ array_push($cond, "p.author_id = ".$this->user->id); }
        if ( $this->user->post_can_read & (UserBlogI::OTHER)){ array_push($cond, "p.author_id != ".$this->user->id); }
        if ( !empty($cond) ){ $cond = 'WHERE '.join(' OR ',$cond); }
        $query = $this->db->query('SELECT
                               p.id id,
                               p.title title,
                               p.content content,
                               p.post_date post_date,
                               u.name author
                             FROM posts p
                             INNER JOIN users u
                             ON p.author_id = u.id
                             '.$cond.'
                             ORDER BY p.id DESC');
        $data = $query->fetchAll();
        $query->closeCursor();
        return $data;
    }
    public function publish($id)
    {
    }
}
