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
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$creator = $this->Session->get('user_id');
			if (!$creator) {
				$this->Session->setFlash('error', 'User not logged in.');
				return $this->Url->redirectToUrl($_SERVER['HTTP_REFERER'] ?? 'home');
			}
			$commentId = $_POST['comment'];
			$type = $_POST['type'];
			try {
				$this->postCommentInteractionModel->create($commentId, $type, $creator);
				$this->Session->setFlash('Post comment interaction added successfully.', 'success');
				return $this->Url->redirect('home');
			} catch (\Throwable $th) {
				$this->Session->setFlash('Failed to add comment interaction: ' . $th->getMessage(), 'error');
				return $this->Url->redirect('home');
			}
		} else {
			$this->Response->status(405)->setHeader('Allow', 'POST')->setResponse(['error' => 'Method Not Allowed'])->send();
		}
	}
}
