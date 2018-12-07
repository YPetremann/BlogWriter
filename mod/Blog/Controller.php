<?php
namespace Blog;
class Controller {
    public function __construct($as){
        $this->user = $as;
    }
    public function listPost(){
        global $view;
        try {
            $postManager = new PostManager($this->user);
            $posts = $postManager->list();
            $view->content = include "dat/view/BlogList.phtml";
        } catch(\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
            return false;
        }
    }
    public function createComment($id, $post) {
        global $view;
        try {
            $id = (int) $id;
            $comment = nl2br(htmlspecialchars((string) $post['comment'] ?: ""));

            if (empty($comment)) {
                throw new \Exception('Tous les champs ne sont pas remplis !');
            }

            $commentManager = new CommentManager($this->user);
            $affectedLines = $commentManager->create($id, $comment);

            if (!$affectedLines) {
                return new \Exception("Impossible d'ajouter le commentaire !");
            }
        } catch(\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
            return false;
        }
    }
    public function reportComment($post_id, $comment_id) {
        global $view;
        try {
            $post_id = (int) $post_id;
            $comment_id = (int) $comment_id;

            $commentManager = new CommentManager($this->user);
            $affectedLines = $commentManager->report($comment_id);

            if (!$affectedLines) {
                throw new \Exception("Vous ne pouvez signaler ce commentaire !");
            } else {
                $view->message .= '<div class="success"><div class="fixer">Le commentaire à été signalé !</div></div>';
            }
        } catch(\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
            return false;
        }
    }

    public function unreportComment($post_id, $comment_id) {
        global $view;
        try {
            $post_id = (int) $post_id;
            $comment_id = (int) $comment_id;

            $commentManager = new CommentManager($this->user);
            $affectedLines = $commentManager->unreport($comment_id);

            if (!$affectedLines) {
                throw new \Exception("Vous ne pouvez désignaler ce commentaire !");
            } else {
                $view->message .= '<div class="success"><div class="fixer">Le commentaire à été désignalé !</div></div>';
            }
        } catch(\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
            return false;
        }
    }
    public function deleteComment($post_id, $comment_id) {
        global $view;
        try {
            $post_id = (int) $post_id;
            $comment_id = (int) $comment_id;

            $commentManager = new CommentManager($this->user);
            $affectedLines = $commentManager->delete($comment_id);

            if (!$affectedLines) {
                throw new \Exception("Vous ne pouvez suprimer ce commentaire !");
            } else {
                $view->message .= '<div class="success"><div class="fixer">Le commentaire à été suprimé !</div></div>';
            }
        } catch(\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
            return false;
        }
    }
    public function publishPost($post_id) {
        global $view;
        try {
            $post_id = (int) $post_id;

            $postManager = new PostManager($this->user);
            $affectedLines = $postManager->set_visibility($post_id,true);

            if (!$affectedLines) {
                throw new \Exception("Vous ne pouvez suprimer ce commentaire !");
            } else {
                $view->message .= '<div class="success"><div class="fixer">Le commentaire à été suprimé !</div></div>';
            }
        } catch(\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
            return false;
        }
    }
    public function readPost($id) {
        global $view;
        try {
            $id = (int) $id;
            $postManager = new PostManager($this->user);
            $post = $postManager->read($id);

            $commentManager = new CommentManager($this->user);
            $post['comments'] = $commentManager->list($id);

            $view->content = include "dat/view/BlogPost.phtml";
        } catch(\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
            return false;
        }
    }
}
