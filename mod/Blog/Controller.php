<?php
namespace Blog;
class Controller {
    public function lists(){
        $postManager = new PostManager();
        return $postManager->list();
    }
    public function post($id) {
        $postManager = new PostManager();
        $post = $postManager->read($id);

        $commentManager = new CommentManager();
        $post['comments'] = $commentManager->list($id);

        return $post;
    }
}
