<?php

use core\Controller;
use core\ImageProcessor;
use app\models\Post;

class PostController extends Controller
{
	private Post $postModel;

	public function __construct($router)
	{
		parent::__construct($router);
		$this->postModel = new Post();
	}

	/**
	 * Show the post feed.
	 * 
	 * Renders the main gallery page where users can see all the cool cat photos.
	 * 
	 * @return void
	 */
	public function index()
	{
		$this->render('post/index', [], 'Post Page');
	}

	/**
	 * Get posts for infinite scroll.
	 * 
	 * Returns a JSON list of posts.
	 * Used by the frontend JS to load more posts as you scroll down.
	 * 
	 * @param int $page
	 * @param int $limit
	 * @return void
	 */
	public function posts($page, $limit)
	{
		$posts = $this->postModel->paginate($page, $limit);
		$this->Response->setHeader('Content-Type', 'application/json')->setResponse($posts)->send();
	}

	/**
	 * Create a new post.
	 * 
	 * Handles both webcam uploads and file uploads.
	 * Also applies stickers if selected. This is where the magic happens!
	 * 
	 * @return void
	 */
	public function create()
	{
		if ($this->isPost()) {
			$creator = $this->userId();
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
			}
		}

		$stickersDir = __DIR__ . '/../public/img/stickers';
		$stickers = ImageProcessor::getAvailableStickers($stickersDir);

		$userPosts = $this->postModel->findByCreator($this->userId());

		$this->render('post/create', [
			'stickers' => $stickers,
			'userPosts' => $userPosts
		], 'Post Create Page');
	}

	/**
	 * Delete a post.
	 * 
	 * Removes the post and its image file.
	 * Checks if you actually own the post first, obviously.
	 * 
	 * @param int $id
	 * @return void
	 */
	public function delete($id)
	{
		$userId = $this->userId();
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
		} catch (\Throwable $th) {
			$this->flash('error', 'Failed to delete post: ' . $th->getMessage());
		} finally {
			return $this->redirect('home');
		}
	}
}
