<?php

use core\Controller;
use core\ImageProcessor;
use core\Security;
use app\models\Post;

class PostController extends Controller
{
	private $postModel;

	public function __construct($router)
	{
		parent::__construct($router);
		$this->postModel = new Post();
	}

	public function index()
	{
		$this->render('post/index', ['message' => 'Hello, World!'], 'Post Page');
	}

	public function posts($page, $limit)
	{
		$posts = $this->postModel->paginate($page, $limit);
		$this->Response->setHeader('Content-Type', 'application/json')->setResponse($posts)->send();
	}

	public function create()
	{
		if ($this->isPost()) {
			$this->validateCSRF('post/create');

			$creator = $this->Session->get('user_id');
			if (!$creator) {
				$this->flash('error', 'User not logged in.');
				return $this->Url->redirectToUrl($_SERVER['HTTP_REFERER'] ?? 'home');
			}

			$title = $this->getPostData('title', '');
			$body = $this->getPostData('body', '');
			$stickerId = $this->getPostData('sticker_id');
			$mediaSrc = null;

			try {
				if (!empty($_POST['webcam_image'])) {
					$base64Image = $_POST['webcam_image'];
					$image = ImageProcessor::base64ToImage($base64Image);

					if ($stickerId) {
						$stickersDir = __DIR__ . '/../public/img/stickers';
						$stickerPath = ImageProcessor::getStickerPath($stickerId, $stickersDir);
						$image = ImageProcessor::mergeImages($image, $stickerPath);
					}

					$filename = 'webcam_' . uniqid() . '.png';
					$uploadPath = __DIR__ . '/../public/img/uploads/' . $filename;
					ImageProcessor::saveImage($image, $uploadPath);
					imagedestroy($image);

					$mediaSrc = 'img/uploads/' . $filename;
				} elseif (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
					$file = $_FILES['media'];
					$uploadedPath = $this->File->upload($file, 'img/uploads');

					if ($stickerId) {
						$image = imagecreatefromstring(file_get_contents(__DIR__ . '/../public/' . $uploadedPath));
						$stickersDir = __DIR__ . '/../public/img/stickers';
						$stickerPath = ImageProcessor::getStickerPath($stickerId, $stickersDir);
						$image = ImageProcessor::mergeImages($image, $stickerPath);

						$fullPath = __DIR__ . '/../public/' . $uploadedPath;
						ImageProcessor::saveImage($image, $fullPath);
						imagedestroy($image);
					}

					$mediaSrc = $uploadedPath;
				} else {
					throw new \Exception('No image provided');
				}

				if ($this->postModel->create($creator, $title, $body, $mediaSrc)) {
					$this->flash('success', 'Post created successfully!');
					return $this->redirect('home');
				}
			} catch (\Throwable $th) {
				$this->flash('error', 'Failed to create post! ' . $th->getMessage());
				return $this->render('post/create');
			}
		}

		$stickersDir = __DIR__ . '/../public/img/stickers';
		$stickers = ImageProcessor::getAvailableStickers($stickersDir);

		$userId = $this->Session->get('user_id');
		$userPosts = $this->postModel->findByCreator($userId);

		$this->render('post/create', [
			'stickers' => $stickers,
			'userPosts' => $userPosts
		], 'Post Create Page');
	}

	public function delete($id)
	{
		if (!$this->isPost()) {
			$this->flash('error', 'Invalid request method');
			return $this->redirect('home');
		}

		$this->validateCSRF();

		$userId = $this->Session->get('user_id');
		if (!$userId) {
			$this->flash('error', 'Unauthorized');
			return $this->redirect('login');
		}

		$post = $this->postModel->find($id);
		if (!$post) {
			$this->flash('error', 'Post not found');
			return $this->redirect('home');
		}

		if ($post['creator'] != $userId) {
			$this->flash('error', 'Forbidden');
			return $this->redirect('home');
		}

		try {
			if ($post['media_src']) {
				$this->File->removeIfExists($post['media_src']);
			}

			$this->postModel->delete($id);

			$this->flash('success', 'Post deleted successfully!');
			return $this->redirect('home');
		} catch (\Throwable $th) {
			$this->flash('error', 'Failed to delete post: ' . $th->getMessage());
			return $this->redirect('home');
		}
	}
}
