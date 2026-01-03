<?php

use core\Controller;
use app\models\PostComment;
use app\models\Post;
use app\models\User;
use core\Mail;

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
		if (!$this->isPost()) {
			return $this->Response->status(405)->setHeader('Allow', 'POST')->setResponse(['error' => 'Method Not Allowed'])->send();
		}

		$referer = $_SERVER['HTTP_REFERER'] ?? 'home';
		$this->validateCSRF($referer);

		$creator = $this->userId();
		$postId = $this->getPostData('post_id');
		$comment = $this->getPostData('comment');

		try {
			$this->postCommentModel->create($postId, $creator, $comment);

			// Notification Logic
			$post = $this->postModel->find($postId);
			if ($post && $post['creator'] != $creator) {
				$postOwner = $this->userModel->find($post['creator']);
				if ($postOwner && $postOwner['notifications_enabled']) {
					$commenter = $this->userNickname();
					$subject = "New comment on your post";
					$message = "
                    <html>
                    <body>
                        <p>Hello {$postOwner['name']},</p>
                        <p><strong>{$commenter}</strong> has commented on your post!</p>
                        <p>Comment: <em>{$comment}</em></p>
                        <p>Click <a href='{$referer}'>here</a> to view it.</p>
                    </body>
                    </html>";
					Mail::send($postOwner['email'], $subject, $message);
				}
			}

			$this->flash('success', 'Post comment added successfully.');
		} catch (\Throwable $th) {
			$this->flash('error', 'Failed to add comment: ' . $th->getMessage());
		}

		return $this->Url->redirectToUrl($referer);
	}
}
