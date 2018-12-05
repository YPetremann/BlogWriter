<?php
namespace Blog;

use \DBManager;
use \USer\User;
class CommentManager
{
    public function __construct($as){
        $this->db = DBManager::get();
        $this->user = $as;
    }
    public function get_comments($id) {}
    public function create($id,$author,$comment) {
        $query = $this->db->prepare("INSERT INTO comments(post_id, author_id, content)
	                           VALUES (?, ?, ?)");
        $answer = $query->execute([$id,0,$comment]);
        return $answer;
    }
    public function read($id) {}
    public function update($id) {}
    public function delete($id) {}
    public function list($id)
    {
        $query = $this->db->prepare('SELECT
                               c.id id,
                               c.content content,
                               c.post_date post_date,
                               u.name author,
                               c.reported reported
                             FROM comments c
                             INNER JOIN users u
                             ON c.author_id = u.id
                             WHERE c.post_id = ?
                             ORDER BY c.id DESC');
        $query->execute([$id]);
        $data = $query->fetchAll();
        $query->closeCursor();
        return $data;
    }
    public function report($id) {
        $cond = [];
        $id = (int) $id;
        if ( $this->user->comment_can_report & (UserBlogI::PUBLIC)){ array_push($cond, "visibility = 1"); }
        if ( $this->user->comment_can_report & (UserBlogI::PRIVATE)){ array_push($cond, "visibility = 0"); }
        if ( $this->user->comment_can_report & (UserBlogI::SELF)){ array_push($cond, "author_id = ".$this->user->id); }
        if ( $this->user->comment_can_report & (UserBlogI::OTHER)){ array_push($cond, "author_id != ".$this->user->id); }
        if ( !empty($cond) ){ $cond = " AND (".join(" OR ",$cond).")"; }
        $query = $this->db->prepare('UPDATE comments SET reported = 1 WHERE reported = 0 AND id = :id'.$cond);
        $query->execute(["id"=>$id]);
        $answer = $query->rowCount();
        $query->closeCursor();
        return $answer;
    }
    public function moderate($id) {}
}
