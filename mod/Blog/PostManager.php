<?php
namespace Blog;

use \DBManager;

class PostManager
{
    public function __construct($as){
        $this->db = DBManager::get();
        $this->user = $as;
    }
    private function permission($perm, $entry){
        $result = false;
        if ( ($perm & UserBlogI::PUBLIC) && $entry["visibility"] == 1){ $result = true; }
        if ( ($perm & UserBlogI::PRIVATE) && $entry["visibility"] != 1){ $result = true; }
        if ( ($perm & UserBlogI::SELF) && $entry["author_id"] == $this->user->id){ $result = true; }
        if ( ($perm & UserBlogI::OTHER) && $entry["author_id"] != $this->user->id){ $result = true; }
        return $result;
    }
    private function sql_permission($perm, $cond){
        if ( $perm & UserBlogI::PUBLIC){ array_push($cond, "visibility = 1"); }
        if ( $perm & UserBlogI::PRIVATE){ array_push($cond, "visibility = 0"); }
        if ( $perm & UserBlogI::SELF){ array_push($cond, "author_id = ".$this->user->id); }
        if ( $perm & UserBlogI::OTHER){ array_push($cond, "author_id != ".$this->user->id); }
        return $cond;
    }
    public function get_comments($id)
    {
    }
    public function create($data)
    {
    }
    public function read($id)
    {
        $cond = [];
        $id = (int) $id;
        $cond = $this->sql_permission($this->user->post_can_read, $cond);
        if ( !empty($cond) ){ $cond = " AND (".join(" OR ",$cond).")"; }
        $query = $this->db->prepare('SELECT
                                 p.id id,
                                 p.title title,
                                 p.content content,
                                 p.visibility visibility,
                                 p.author_id author_id,
                                 p.post_date post_date,
                                 u.name author
                               FROM posts p
                               INNER JOIN users u
                               ON p.author_id = u.id
                               WHERE p.id = ?'.$cond);
        $query->execute([$id]);
        $posts = $query->fetchAll();
        $query->closeCursor();

        if(count($posts) != 1) {throw new \Exception("Article inexistant");}
        $post = $posts[0];
        $post["comment_can_create"] = $this->permission($this->user->comment_can_create, $post);
        return $post;
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
