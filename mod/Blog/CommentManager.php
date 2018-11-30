<?php
namespace Blog;

use \DBManager;

class CommentManager extends DBManager
{
    public function get_comments($id) {}
    public function create($data) {}
    public function read($id) {}
    public function update($id) {}
    public function delete($id) {}
    public function list($id)
    {
        $db = $this->db_connect();
        $query = $db->prepare('SELECT
                               c.id id,
                               c.content content,
                               c.post_date post_date,
                               u.name author
                             FROM comments c
                             INNER JOIN users u
                             ON c.author_id = u.id
                             WHERE c.id = ?
                             ORDER BY c.id DESC');
        $query->execute([$id]);
        $data = $query->fetchAll();
        $query->closeCursor();
        return $data;
    }
    public function report($id) {}
    public function moderate($id) {}
}
