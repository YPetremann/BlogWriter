<?php
namespace Blog;

use \DBManager;

class PostManager
{
    public function __construct(UserBlogI $as)
    {
        $this->db = DBManager::get();
        $this->user = $as;
    }
    private function permission($perm, $entry)
    {
        $result = false;
        if (($perm & UserBlogI::PUBLIC) && $entry["visibility"] == 1) { $result = true; }
        if (($perm & UserBlogI::PRIVATE) && $entry["visibility"] != 1) { $result = true; }
        if (($perm & UserBlogI::SELF) && $entry["author_id"] == $this->user->id) { $result = true; }
        if (($perm & UserBlogI::OTHER) && $entry["author_id"] != $this->user->id) { $result = true; }
        return $result;
    }
    private function sql_permission($perm)
    {
        $cond = [];
        if ($perm & UserBlogI::PUBLIC) { array_push($cond, "visibility = 1"); }
        if ($perm & UserBlogI::PRIVATE) { array_push($cond, "visibility = 0"); }
        if ($perm & UserBlogI::SELF) { array_push($cond, "author_id = ".$this->user->id); }
        if ($perm & UserBlogI::OTHER) { array_push($cond, "author_id != ".$this->user->id); }
        return empty($cond) ? " AND FALSE" : " AND (".join(" OR ", $cond).")";
    }
    public function create(string $title, string $content) {
        // execute query
        $query = $this->db->prepare("
            INSERT INTO posts(title, author_id, content)
            VALUES (?, ?, ?)");
        $answer = $query->execute([$title, $this->user->id, $content]);
        return $this->db->lastInsertId();
    }
    public function read($id)
    {
        $id = (int) $id;

        // apply user permission to sql query
        $cond = $this->sql_permission($this->user->post_can_read);

        // execute query
        $query = $this->db->prepare('
            SELECT
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

        // verify if there is one post
        if (count($posts) != 1) { throw new \Exception("Can't access post."); }
        $post = $posts[0];

        // insert permission in post
        $post["comment_can_create"] = $this->permission($this->user->comment_can_create, $post);
        $post["post_can_update"] = $this->permission($this->user->post_can_update, $post);
        $post["post_can_publish"] = $this->permission($this->user->post_can_publish, $post);
        $post["post_can_delete"] = $this->permission($this->user->post_can_delete, $post);
        return $post;
    }
    public function update(int $id, string $title, string $content) {

        // apply user permission to sql query
        $cond = $this->sql_permission($this->user->post_can_update);
        // execute query
        $query = $this->db->prepare('UPDATE posts SET title = ?, content = ? WHERE id = ?'.$cond);
        $query->execute([$title, $content, $id]);
        $answer = $query->rowCount();
        $query->closeCursor();

        return $answer;
    }
    public function delete($id) {
        $id = (int) $id;

        $cond = $this->sql_permission($this->user->comment_can_delete);
        $query = $this->db->prepare('
            DELETE FROM posts
            WHERE id = :id'.$cond);
        $query->execute(["id"=>$id]);
        $answer = $query->rowCount();
        $query->closeCursor();
        return $answer;
    }
    public function list()
    {
        // apply user permission to sql query
        $cond = $this->sql_permission($this->user->post_can_read);

        // execute query
        $query = $this->db->query('
            SELECT
                p.id id,
                p.title title,
                p.content content,
                LEFT(p.content,512) excerpt,
                p.visibility visibility,
                p.author_id author_id,
                p.post_date post_date,
                u.name author
            FROM posts p
            INNER JOIN users u
            ON p.author_id = u.id
            '.$cond.'
            ORDER BY p.id DESC');
        $data = $query->fetchAll();

        // insert permission in post
        foreach ($data as &$entry) {
            if(strlen($entry["excerpt"]) != strlen($entry["content"])) $entry["excerpt"] = substr($entry["excerpt"],0,strrpos($entry["excerpt"]," "))." ...";
            $entry["post_can_update"] = $this->permission($this->user->post_can_update, $entry);
            $entry["post_can_publish"] = $this->permission($this->user->post_can_publish, $entry);
            $entry["post_can_delete"] = $this->permission($this->user->post_can_delete, $entry);
        }
        $query->closeCursor();

        return $data;
    }
    public function publish($id)
    {
        $id = (int) $id;

        // apply user permission to sql query
        $cond = $this->sql_permission($this->user->post_can_publish);

        // execute query
        $query = $this->db->prepare('UPDATE posts SET visibility = 1 WHERE visibility = 0 AND id = ?'.$cond);
        $query->execute([$id]);
        $answer = $query->rowCount();
        $query->closeCursor();

        return $answer;
    }
    public function unpublish($id)
    {
        $id = (int) $id;

        // apply user permission to sql query
        $cond = $this->sql_permission($this->user->post_can_unpublish);

        // execute query
        $query = $this->db->prepare('
            UPDATE posts
            SET visibility = 0
            WHERE visibility = 1 AND id = ?'.$cond);
        $query->execute([$id]);
        $answer = $query->rowCount();
        $query->closeCursor();

        return $answer;
    }
}
