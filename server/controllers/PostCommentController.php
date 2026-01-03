<?php

use core\Controller;
use app\models\PostComment;
use app\models\Post;
use app\models\User;
use core\Mail;
use core\Security;

class PostCommentController extends Controller
{
	private $postCommentModel;
	private $postModel;
	private $userModel;

	public function __construct($router)
	{
		parent::__construct($router);
		$this->postCommentModel = new PostComment();
		$this->postModel = new Post();
		$this->userModel = new User();
	}

	public function create()
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
			$comment = $_POST['comment'];
			try {
				$commentId = $this->postCommentModel->create($postId, $creator, $comment);

				// Notification Logic
				$post = $this->postModel->findById($postId);
				if ($post && $post['creator'] != $creator) {
					$postOwner = $this->userModel->findById($post['creator']);
					if ($postOwner && $postOwner['notifications_enabled']) {
						$commenter = $this->Session->get('user_nickname');
						$subject = "New comment on your post";
						$message = "
                        <html>
                        <body>
                            <p>Hello {$postOwner['name']},</p>
                            <p><strong>{$commenter}</strong> has commented on your post!</p>
                            <p>Comment: <em>{$comment}</em></p>
                            <p>Click <a href='{$_SERVER['HTTP_REFERER']}'>here</a> to view it.</p>
                        </body>
                        </html>
                        ";
						Mail::send($postOwner['email'], $subject, $message);
					}
				}

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
