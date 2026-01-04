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

	/**
	 * Toggle like on a post.
	 * 
	 * If you liked it, it unlikes it.
	 * If you haven't liked it, it likes it.
	 * Simple toggle logic.
	 * 
	 * @return void
	 */
	public function toggle()
	{
		if ($this->isPost()) {
			$referer = $_SERVER['HTTP_REFERER'] ?? '/';
			$this->validateCSRF($referer);

			$creator = $this->userId();
			$postId = $this->getPostData('post_id');
			$like = $this->postLikeModel->toggle($creator, $postId);
			$this->flash('success', $like ? 'Post liked successfully.' : 'Post unliked successfully.');
			$this->Url->redirectToUrl($referer);
		}
	}
}
