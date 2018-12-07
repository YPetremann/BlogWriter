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
    private function permission($perm, $entry){
        $result = false;
        if ( ($perm & UserBlogI::PUBLIC ) && $entry["visibility"] == 1 ){ $result = true; }
        if ( ($perm & UserBlogI::PRIVATE) && $entry["visibility"] != 1 ){ $result = true; }
        if ( ($perm & UserBlogI::SELF   ) && $entry["author_id"] == $this->user->id ){ $result = true; }
        if ( ($perm & UserBlogI::OTHER  ) && $entry["author_id"] != $this->user->id ){ $result = true; }
        return $result;
    }
    private function sql_permission($perm, $cond){
        if ( $perm & UserBlogI::PUBLIC  ){ array_push($cond, "visibility = 1"); }
        if ( $perm & UserBlogI::PRIVATE ){ array_push($cond, "visibility = 0"); }
        if ( $perm & UserBlogI::SELF    ){ array_push($cond, "author_id = ".$this->user->id); }
        if ( $perm & UserBlogI::OTHER   ){ array_push($cond, "author_id != ".$this->user->id); }
        return $cond;
    }
    public function get_comments($id) {}
    public function create($id,$comment) {
        $id = (int) $id;

        $postManager = new PostManager($this->user);
        $post = $postManager->read($id);
        if(!$post['comment_can_create']) throw new \Exception("Vous ne pouvez poster de commentaires ici !");

        $query = $this->db->prepare("INSERT INTO comments(post_id, author_id, content)
	                           VALUES (?, ?, ?)");
        $answer = $query->execute([$id,$this->user->id,$comment]);
        return $answer;
    }
    public function read($id) {}
    public function update($id) {}
    public function delete($id) {
        $id = (int) $id;

        $cond = [];
        $cond = $this->sql_permission($this->user->comment_can_delete, $cond);
        if ( !empty($cond) ){ $cond = " AND (".join(" OR ",$cond).")"; }
        $query = $this->db->prepare('DELETE FROM comments WHERE id = :id'.$cond);
        $query->execute(["id"=>$id]);
        $answer = $query->rowCount();
        $query->closeCursor();
        return $answer;
    }
    public function list($id)
    {
        $query = $this->db->prepare('SELECT
                               c.id id,
                               c.content content,
                               c.author_id author_id,
                               c.post_date post_date,
                               c.visibility visibility,
                               u.name author,
                               c.reported reported
                             FROM comments c
                             INNER JOIN users u
                             ON c.author_id = u.id
                             WHERE c.post_id = ?
                             ORDER BY c.id DESC');
        $query->execute([$id]);
        $data = $query->fetchAll();
        foreach($data as &$entry){
            $entry["comment_can_delete"]=$this->permission($this->user->comment_can_delete, $entry);
            $entry["comment_can_report"]=$this->permission($this->user->comment_can_report, $entry);
            $entry["comment_can_unreport"]=$this->permission($this->user->comment_can_unreport, $entry);
        }
        $query->closeCursor();
        return $data;
    }
    public function report($id) {
        $id = (int) $id;

        $cond = [];
        $cond = $this->sql_permission($this->user->comment_can_report, $cond);
        if ( !empty($cond) ){ $cond = " AND (".join(" OR ",$cond).")"; }

        $query = $this->db->prepare('UPDATE comments SET reported = 1 WHERE reported = 0 AND id = :id'.$cond);
        $query->execute(["id"=>$id]);
        $answer = $query->rowCount();
        $query->closeCursor();
        return $answer;
    }
    public function unreport($id) {
        $id = (int) $id;

        $cond = [];
        $cond = $this->sql_permission($this->user->comment_can_unreport, $cond);
        if ( !empty($cond) ){ $cond = " AND (".join(" OR ",$cond).")"; }

        $query = $this->db->prepare('UPDATE comments SET reported = 0 WHERE reported = 1 AND id = :id'.$cond);
        $query->execute(["id"=>$id]);
        $answer = $query->rowCount();
        $query->closeCursor();
        return $answer;
    }
    public function moderate($id) {}
}
