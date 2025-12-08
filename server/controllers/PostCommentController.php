<?php

use core\Controller;
use app\models\PostComment;

class PostCommentController extends Controller
{
	private $postCommentModel;

	public function __construct($router)
	{
		parent::__construct($router);
		$this->postCommentModel = new PostComment();
	}

	public function create()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$creator = $this->Session->get('user_id');
			$postId = $_POST['post_id'];
			$comment = $_POST['comment'];
			try {
				$commentId = $this->postCommentModel->create($postId, $creator, $comment);
				$this->Session->setFlash('success', 'Post comment added successfully.');
				return $this->Url->redirectToUrl($_SERVER['HTTP_REFERER'] ?? 'home');
			} catch (\Throwable $th) {
				$this->Session->setFlash('error', 'Failed to add comment: ' . $th->getMessage());
				return $this->Url->redirectToUrl($_SERVER['HTTP_REFERER'] ?? 'home');
			}
		} else {
			$this->Response->status(405)->setHeader('Allow', 'POST')->setResponse(['error' => 'Method Not Allowed'])->send();
		}
	}
}
