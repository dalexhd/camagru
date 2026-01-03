<?php

use core\Controller;
use app\models\PostLikes;

class PostLikeController extends Controller
{
	private $postLikeModel;

	public function __construct($router)
	{
		parent::__construct($router);
		$this->postLikeModel = new PostLikes();
	}

	public function toggle()
	{
		if ($this->isPost()) {
			$referer = $_SERVER['HTTP_REFERER'] ?? '/';
			$this->validateCSRF($referer);

			$creator = $this->Session->get('user_id');
			if (!$creator) {
				$this->flash('error', 'User not logged in.');
				return $this->Url->redirectToUrl($referer);
			}

			$postId = $this->getPostData('post_id');
			$like = $this->postLikeModel->toggle($creator, $postId);
			$this->flash('success', $like ? 'Post liked successfully.' : 'Post unliked successfully.');
			$this->Url->redirectToUrl($referer);
		}
	}
}
