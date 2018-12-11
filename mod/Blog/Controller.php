<?php
namespace Blog;

class Controller
{
    public function __construct($as)
    {
        $this->user = $as;
    }

    public function listPost()
    {
        global $view;
        try {
            $posts = (new PostManager($this->user) )->list();
            $view->content = include "dat/view/BlogList.phtml";
        } catch (\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
            return false;
        }
    }

    public function createComment($id, $post)
    {
        global $view;
        try {
            $id = (int) $id;
            $comment = nl2br(htmlspecialchars((string) $post['comment'] ?: ""));

            if (empty($comment)) {
                throw new \Exception('Tous les champs ne sont pas remplis !');
            }

            $affectedLines = (new CommentManager($this->user) )->create($id, $comment);

            if (!$affectedLines) {
                throw new \Exception("Impossible d'ajouter le commentaire !");
            }
        } catch (\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
        }
        return false;
    }

    public function reportComment($post_id, $comment_id)
    {
        global $view;
        try {
            $post_id = (int) $post_id;
            $comment_id = (int) $comment_id;

            $affectedLines = (new CommentManager($this->user) )->report($comment_id);

            if (!$affectedLines) {
                throw new \Exception("Vous ne pouvez signaler ce commentaire !");
            }
            $view->message .= '<div class="success"><div class="fixer">Le commentaire à été signalé !</div></div>';
        } catch (\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
        }
        return false;
    }

    public function unreportComment($post_id, $comment_id)
    {
        global $view;
        try {
            $post_id = (int) $post_id;
            $comment_id = (int) $comment_id;

            $affectedLines = (new CommentManager($this->user) )->unreport($comment_id);

            if (!$affectedLines) {
                throw new \Exception("Vous ne pouvez désignaler ce commentaire !");
            } else {
                $view->message .= '<div class="success"><div class="fixer">Le commentaire à été désignalé !</div></div>';
            }
        } catch (\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
        }
        return false;
    }

    public function deleteComment($post_id, $comment_id)
    {
        global $view;
        try {
            $post_id = (int) $post_id;
            $comment_id = (int) $comment_id;

            $affectedLines = (new CommentManager($this->user) )->delete($comment_id);

            if (!$affectedLines) {
                throw new \Exception("Vous ne pouvez suprimer ce commentaire !");
            } else {
                $view->message .= '<div class="success"><div class="fixer">Le commentaire à été suprimé !</div></div>';
            }
        } catch (\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
        }
        return false;
    }

    public function publishPost($post_id)
    {
        global $view;
        try {
            $post_id = (int) $post_id;

            $affectedLines = (new PostManager($this->user) )->publish($post_id);

            if (!$affectedLines) {
                throw new \Exception("Vous ne pouvez publier cet article !");
            } else {
                $view->message .= '<div class="success"><div class="fixer">L\'article à été publié !</div></div>';
            }
        } catch (\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
        }
        return false;
    }

    public function readPost($id)
    {
        global $view;
        try {
            $id = (int) $id;

            $post = (new PostManager($this->user) )->read($id);
            $post['comments'] = (new CommentManager($this->user) )->list($id);

            $view->content = include "dat/view/BlogPost.phtml";
        } catch (\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
            return false;
        }
    }

    public function createPost($post)
    {
        global $view;
        try {
            // preformating content
            preg_match('#<body>\s*(.*)\s*</body>#ms', $post['content'], $content);
            $post["content"] = $content[1];
            // verify empty content
            if (empty($post["content"]) || empty($post["title"])) {
                throw new \Exception('Tous les champs ne sont pas remplis !');
            }
            // verify content
            $affectedLines = (new PostManager($this->user) )->create($post['title'], $post['content']);
            if (!$affectedLines) {
                throw new \Exception("Article non ajouté !");
            }
            $view->message .= '<div class="success"><div class="fixer">Article ajouté !</div></div>';
            return $affectedLines;
        } catch (\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
        }
        return false;
    }

    public function updatePost($id, $post)
    {
        global $view;
        try {
            // preformating content
            preg_match('#<body>\s*(.*)\s*</body>#ms', $post['content'], $content);
            $post["content"] = $content[1];
            // verify empty content
            if (empty($post["content"]) || empty($post["title"])) {
                throw new \Exception('Tous les champs ne sont pas remplis !');
            }
            // verify content
            $affectedLines = (new PostManager($this->user) )->update($id, $post['title'], $post['content']);
            if (!$affectedLines) {
                throw new \Exception("Article non mis à jour !");
            }
            $view->message .= '<div class="success"><div class="fixer">Article mis à jour !</div></div>';
        } catch (\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
        }
        return false;
    }

    public function editPost($id=null)
    {
        global $view;
        try {
            $id = (int) $id;
            if ($id != 0) {
                $post = (new PostManager($this->user) )->read($id);
                if (!$post["post_can_update"]) {
                    throw new \Exception("Vous ne pouvez éditer l'article !");
                }
            } else {
                if (!$this->user->post_can_create) {
                    throw new \Exception("Vous ne pouvez créer d'articles !");
                }
                $post = [
                    'id'=>null,
                    'post_date'=>null,
                    'title'=>"",
                    'content'=>"",
                ];
            }
            $view->content = include "dat/view/BlogPostEdit.phtml";
        } catch (\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
            return false;
        }
    }
}
