<?php
namespace Blog;

use \Blog\Model\PostManager;
use \Blog\Model\CommentManager;

class Controller
{
    private $user;
    public function __construct(UserBlogI $as)
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

    public function listComment()
    {
        global $view;
        try {
            $comments = (new CommentManager($this->user) )->list();
            $view->content = include "dat/view/CommentList.phtml";
        } catch (\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
            return false;
        }
    }

    public function createComment(int $id, $post)
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

    public function reportComment(int $comment_id)
    {
        global $view;
        try {
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

    public function unreportComment(int $comment_id)
    {
        global $view;
        try {
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

    public function deleteComment(int $comment_id)
    {
        global $view;
        try {
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

    public function publishPost(int $post_id)
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

    public function readPost(int $id)
    {
        global $view;
        try {
            $id = (int) $id;

            $post = (new PostManager($this->user) )->read($id);
            $post['comments'] = (new CommentManager($this->user) )->list($id);

            $view->content = include "dat/view/BlogPost.phtml";
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            if ($msg == "Can't access post.") {
                $msg = "Vous ne pouvez lire l'article !";
            }
            $view->message .= '<div class="error"><div class="fixer">'.$msg.'</div></div>';
            return false;
        }
    }

    public function createPost($post)
    {
        global $view;
        try {
            // preformating content
            preg_match('#<body>\s*(.*)\s*</body>#ms', $post['content'], $content);
            if (isset($content[1])) {
                $post["content"] = $content[1];
            }
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

    public function updatePost(int $id, $post)
    {
        global $view;
        try {
            // preformating content
            preg_match('#<body>\s*(.*)\s*</body>#ms', $post['content'], $content);
            if (isset($content[1])) {
                $post["content"] = $content[1];
            }
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
                    'visibility'=>0,
                    'post_can_delete'=>0,
                    'post_can_publish'=>0,
                    'post_can_unpublish'=>0,
                ];
            }
            $view->content = include "dat/view/BlogPostEdit.phtml";
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            if ($msg == "Can't access post.") {
                $msg = "Vous ne pouvez éditer l'article !";
            }
            $view->message .= '<div class="error"><div class="fixer">'.$msg.'</div></div>';
            return false;
        }
    }

    public function unpublishPost(int $post_id)
    {
        global $view;
        try {
            $post_id = (int) $post_id;

            $affectedLines = (new PostManager($this->user) )->unpublish($post_id);

            if (!$affectedLines) {
                throw new \Exception("Vous ne pouvez dépublier cet article !");
            } else {
                $view->message .= '<div class="success"><div class="fixer">L\'article à été dépublié !</div></div>';
            }
        } catch (\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
        }
        return false;
    }

    public function deletePost(int $post_id)
    {
        global $view;
        try {
            $post_id = (int) $post_id;

            $affectedLines = (new PostManager($this->user) )->delete($post_id);

            if (!$affectedLines) {
                throw new \Exception("Vous ne pouvez suprimer cet article !");
            } else {
                $view->message .= '<div class="success"><div class="fixer">L\'article à été suprimé !</div></div>';
            }
        } catch (\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
        }
        return false;
    }

    public function publishComment(int $comment_id)
    {
        global $view;
        try {
            $comment_id = (int) $comment_id;

            $affectedLines = (new CommentManager($this->user) )->publish($comment_id);

            if (!$affectedLines) {
                throw new \Exception("Vous ne pouvez publier ce commentaire !");
            } else {
                $view->message .= '<div class="success"><div class="fixer">Le commentaire à été publié !</div></div>';
            }
        } catch (\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
        }
        return false;
    }

    public function unpublishComment(int $comment_id)
    {
        global $view;
        try {
            $comment_id = (int) $comment_id;

            $affectedLines = (new CommentManager($this->user) )->unpublish($comment_id);

            if (!$affectedLines) {
                throw new \Exception("Vous ne pouvez dépublier ce commentaire !");
            } else {
                $view->message .= '<div class="success"><div class="fixer">Le commentaire à été dépublié !</div></div>';
            }
        } catch (\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
        }
        return false;
    }
}
