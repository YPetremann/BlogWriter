<?php
namespace Blog;

use \DBManager;
use \USer\User;

class CommentManager
{
    public function __construct(User $as)
    {
        $this->db = DBManager::get();
        $this->user = $as;
    }
    private function permission(int $perm, $entry)
    {
        $result = false;
        if (($perm & UserBlogI::PUBLIC) && $entry["visibility"] == 1) { $result = true; }
        if (($perm & UserBlogI::PRIVATE) && $entry["visibility"] != 1) { $result = true; }
        if (($perm & UserBlogI::SELF) && $entry["author_id"] == $this->user->id) { $result = true; }
        if (($perm & UserBlogI::OTHER) && $entry["author_id"] != $this->user->id) { $result = true; }
        return $result;
    }
    private function sql_permission(int $perm)
    {
        $cond = [];
        if ($perm & UserBlogI::PUBLIC) { array_push($cond, "visibility = 1"); }
        if ($perm & UserBlogI::PRIVATE) { array_push($cond, "visibility = 0"); }
        if ($perm & UserBlogI::SELF) { array_push($cond, "author_id = ".$this->user->id); }
        if ($perm & UserBlogI::OTHER) { array_push($cond, "author_id != ".$this->user->id); }
        return empty($cond) ? "FALSE" : "(".join(" OR ", $cond).")";
    }
    public function create(int $id, string $comment)
    {
        $id = (int) $id;

        $postManager = new PostManager($this->user);
        $post = $postManager->read($id);
        if (!$post['comment_can_create']) { throw new \Exception("Vous ne pouvez poster de commentaires ici !"); }

        $query = $this->db->prepare("
            INSERT INTO comments(post_id, author_id, content)
            VALUES (?, ?, ?)");
        $answer = $query->execute([$id,$this->user->id,$comment]);
        return $answer;
    }
    public function delete(int $id)
    {
        $id = (int) $id;

        $cond = $this->sql_permission($this->user->comment_can_delete);
        $query = $this->db->prepare('
            DELETE FROM comments
            WHERE id = :id AND '.$cond);
        $query->execute(["id"=>$id]);
        $answer = $query->rowCount();
        $query->closeCursor();
        return $answer;
    }
    public function list($id = null)
    {
        $cond = $this->sql_permission($this->user->comment_can_read);
        if($id != null) $cond .= ' AND c.post_id = ?';
        $query = $this->db->prepare('
            SELECT
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
            WHERE '.$cond.'
            ORDER BY c.visibility DESC, c.reported DESC, c.id DESC');
        $query->execute([$id]);
        $data = $query->fetchAll();
        foreach ($data as &$entry) {
            $entry["comment_can_delete"]=$this->permission($this->user->comment_can_delete, $entry);
            $entry["comment_can_report"]=$this->permission($this->user->comment_can_report, $entry);
            $entry["comment_can_unreport"]=$this->permission($this->user->comment_can_unreport, $entry);
            $entry["comment_can_publish"]=$this->permission($this->user->comment_can_publish, $entry);
            $entry["comment_can_unpublish"]=$this->permission($this->user->comment_can_unpublish, $entry);
        }
        $query->closeCursor();
        return $data;
    }
    public function report(int $id)
    {
        $id = (int) $id;

        $cond = $this->sql_permission($this->user->comment_can_report);

        $query = $this->db->prepare('
            UPDATE comments
            SET reported = 1
            WHERE reported = 0 AND id = :id AND '.$cond);
        $query->execute(["id"=>$id]);
        $answer = $query->rowCount();
        $query->closeCursor();
        return $answer;
    }
    public function unreport(int $id)
    {
        $id = (int) $id;

        $cond = $this->sql_permission($this->user->comment_can_unreport);

        $query = $this->db->prepare('
            UPDATE comments
            SET reported = 0
            WHERE reported = 1 AND id = :id AND '.$cond);
        $query->execute(["id"=>$id]);
        $answer = $query->rowCount();
        $query->closeCursor();
        return $answer;
    }
    public function publish(int $id)
    {
        $id = (int) $id;

        // apply user permission to sql query
        $cond = $this->sql_permission($this->user->comment_can_publish);

        // execute query
        $query = $this->db->prepare('
            UPDATE comments
            SET visibility = 1
            WHERE visibility = 0 AND id = ? AND '.$cond);
        $query->execute([$id]);
        $answer = $query->rowCount();
        $query->closeCursor();

        return $answer;
    }
    public function unpublish(int $id)
    {
        $id = (int) $id;

        // apply user permission to sql query
        $cond = $this->sql_permission($this->user->comment_can_unpublish);

        // execute query
        $query = $this->db->prepare('
            UPDATE comments
            SET visibility = 0
            WHERE visibility = 1 AND id = ? AND '.$cond);
        $query->execute([$id]);
        $answer = $query->rowCount();
        $query->closeCursor();

        return $answer;
    }
}
