<?php
namespace Blog;

use \DBManager;

class PostManager extends DBManager
{
    public function get_comments($id)
    {
    }
    public function create($data)
    {
    }
    public function read($id)
    {
        $db = $this->db_connect();
        $query = $db->prepare('SELECT
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
        $db = $this->db_connect();
        $query = $db->query('SELECT
                               p.id id,
                               p.title title,
                               p.content content,
                               p.post_date post_date,
                               u.name author
                             FROM posts p
                             INNER JOIN users u
                             ON p.author_id = u.id
                             ORDER BY p.id DESC');
        $data = $query->fetchAll();
        $query->closeCursor();
        return $data;
    }
    public function publish($id)
    {
    }
}
