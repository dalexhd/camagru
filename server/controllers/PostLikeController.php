<?php

use core\Controller;
use app\models\PostLikes;

class PostLikeController extends Controller
{
	private PostLikes $postLikesModel;

	public function __construct($router)
	{
		parent::__construct($router);
		$this->postLikesModel = new PostLikes();
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
		$referer = $_SERVER['HTTP_REFERER'] ?? '/';

		$creator = $this->userId();
		$postId = $this->getPostData('post_id');
		$like = $this->postLikesModel->toggle($creator, $postId);
		$this->flash('success', $like ? 'Post liked successfully.' : 'Post unliked successfully.');
		$this->Url->redirectToUrl($referer);
	}
}
