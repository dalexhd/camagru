<?php

use core\Controller;
use core\Security;
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
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$creator = $this->Session->get('user_id');
			if (!$creator) {
				$this->Session->setFlash('error', 'User not logged in.');
				return $this->Url->redirectToUrl($_SERVER['HTTP_REFERER'] ?? 'home');
			}
			if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
				$this->Session->setFlash('error', 'Security token mismatch. Please try again.');
				return $this->Url->redirectToUrl($_SERVER['HTTP_REFERER'] ?? 'home');
			}
			$postId = $_POST['post_id'];
			$like = $this->postLikeModel->toggle($creator, $postId);
			$this->Session->setFlash('success', $like ? 'Post liked successfully.' : 'Post unliked successfully.');
			$this->Url->redirectToUrl($_SERVER['HTTP_REFERER'] ?? '/');
		}
	}
}
