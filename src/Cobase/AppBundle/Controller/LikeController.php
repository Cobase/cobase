<?php
namespace Cobase\AppBundle\Controller;

use Cobase\AppBundle\Controller\BaseController;

class LikeController extends BaseController
{
    public function likePostAction($postId)
    {
        $post = $this->getPostService()->getPostById($postId);
        $user = $this->getCurrentUser();

        if ($user->likesPost($post)) {
            return $this->createJsonFailureResponse(array(
                'message' => 'You already like this post',
            ));
            return $this->getJsonResponse(array('status' => false, 'message' => 'You already like this post'));
        }

        $this->getLikeService()->likePost($post, $user);

        return $this->getJsonResponse(array('status' => true, 'message' => 'You now like this post'));
    }
}
