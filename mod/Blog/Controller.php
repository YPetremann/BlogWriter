<?php
namespace Blog;
class Controller {
    public function listPost(){
        $postManager = new PostManager($_SESSION["user"]);
        return $postManager->list();
    }
    public function createComment($id, $post) {
        $id = (int) $id;
        $author = htmlspecialchars(Strip_tags((string) $post['author'] ?: ""));
        $comment = nl2br(htmlspecialchars((string) $post['comment'] ?: ""));

        if (empty($author) or empty($comment)) {
            throw new Exception('Tous les champs ne sont pas remplis !');
        }

        $commentManager = new CommentManager($_SESSION["user"]);
        $affectedLines = $commentManager->create($id, $author, $comment);

        if (!$affectedLines) {
            return new Exception("Impossible d'ajouter le commentaire !");
        } else {
            header('Location: '.$_SERVER['REQUEST_URI']);
        }
        return true;
    }
    public function readPost($id) {
        $id = (int) $id;
        $postManager = new PostManager($_SESSION["user"]);

        $post = $postManager->read($id);

        $commentManager = new CommentManager($_SESSION["user"]);
        $post['comments'] = $commentManager->list($id);

        return $post;
    }
}
