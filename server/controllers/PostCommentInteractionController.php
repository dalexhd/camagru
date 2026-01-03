<?php

use core\Controller;
use app\models\PostCommentInteraction;

class PostCommentInteractionController extends Controller
{
	private $postCommentInteractionModel;

	public function __construct($router)
	{
		parent::__construct($router);
		$this->postCommentInteractionModel = new PostCommentInteraction();
	}

	public function create()
	{
		if (!$this->isPost()) {
			return $this->Response->status(405)->setHeader('Allow', 'POST')->setResponse(['error' => 'Method Not Allowed'])->send();
		}

		$referer = $_SERVER['HTTP_REFERER'] ?? 'home';
		$this->validateCSRF($referer);

		$creator = $this->Session->get('user_id');
		if (!$creator) {
			$this->flash('error', 'User not logged in.');
			return $this->Url->redirectToUrl($referer);
		}

		$commentId = $this->getPostData('comment');
		$type = $this->getPostData('type');

		try {
			$this->postCommentInteractionModel->create($commentId, $type, $creator);
			$this->flash('success', 'Post comment interaction added successfully.');
		} catch (\Throwable $th) {
			$this->flash('error', 'Failed to add comment interaction: ' . $th->getMessage());
		}

		return $this->redirect('home');
	}
}
