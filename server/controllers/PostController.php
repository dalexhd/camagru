<?php

use core\Controller;
use core\ImageProcessor;
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
		$this->View->render('post/index', ['message' => 'Hello, World!'], 'Post Page');
	}

	public function posts($page, $limit)
	{
		$posts = $this->postModel->paginate($page, $limit);
		/* 		$comments = [
			[
				'author' => 'Author 1',
				'avatar' => 'https://picsum.photos/24',
				'text' => 'First comment, hello world!',
				'created_at' => date(strtotime('-1 hour'))
			],
			[
				'author' => 'Author 2',
				'avatar' => 'https://picsum.photos/24',
				'text' => 'Second large comment, hello world! Lorem ipsum dolor sit amet.',
				'created_at' => date(strtotime('-2 hours'))
			],
			[
				'author' => 'Author 3',
				'avatar' => 'https://picsum.photos/24',
				'text' => 'Third comment, hello world!',
				'created_at' => date(strtotime('-1 day'))
			],
			[
				'author' => 'Author 4',
				'avatar' => 'https://picsum.photos/24',
				'text' => 'Fourth comment, hello world!',
				'created_at' => date(strtotime('-1 week'))
			],
		];

		$posts = [];
		for ($i = 0; $i < 20; $i++) {
			$posts[] = [
				'author' => [
					'username' => 'Author ' . ($i + 1),
					'avatar' => 'https://picsum.photos/24'
				],
				'id' => $i + 1,
				'text' => 'First post, hello world!',
				'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour')),  // Correctly formatted date.
				'src' => 'https://picsum.photos/1920/1080',
				'comments' => $comments
			];
		} */
		$this->Response->setHeader('Content-Type', 'application/json')->setResponse($posts)->send();
	}

	public function create()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$creator = $this->Session->get('user_id');
			if (!$creator) {
				$this->Session->setFlash('error', 'User not logged in.');
				return $this->Url->redirectToUrl($_SERVER['HTTP_REFERER'] ?? 'home');
			}
			$title = $_POST['title'] ?? '';
			$body = $_POST['body'] ?? '';
			$stickerId = $_POST['sticker_id'] ?? null;
			$mediaSrc = null;

			try {
				// Check if this is a webcam capture (Base64) or file upload
				if (!empty($_POST['webcam_image'])) {
					// Handle webcam Base64 image
					$base64Image = $_POST['webcam_image'];
					$image = ImageProcessor::base64ToImage($base64Image);

					// Merge with sticker if selected
					if ($stickerId) {
						$stickersDir = __DIR__ . '/../public/img/stickers';
						$stickerPath = ImageProcessor::getStickerPath($stickerId, $stickersDir);
						$image = ImageProcessor::mergeImages($image, $stickerPath);
					}

					// Save merged image
					$filename = 'webcam_' . uniqid() . '.png';
					$uploadPath = __DIR__ . '/../public/img/uploads/' . $filename;
					ImageProcessor::saveImage($image, $uploadPath);
					imagedestroy($image);

					$mediaSrc = 'img/uploads/' . $filename;
				} elseif (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
					// Handle traditional file upload
					$file = $_FILES['media'];
					$uploadedPath = $this->File->upload($file, 'img/uploads');

					// Merge with sticker if selected
					if ($stickerId) {
						$image = imagecreatefromstring(file_get_contents(__DIR__ . '/../public/' . $uploadedPath));
						$stickersDir = __DIR__ . '/../public/img/stickers';
						$stickerPath = ImageProcessor::getStickerPath($stickerId, $stickersDir);
						$image = ImageProcessor::mergeImages($image, $stickerPath);

						// Save merged image (overwrite uploaded file)
						$fullPath = __DIR__ . '/../public/' . $uploadedPath;
						ImageProcessor::saveImage($image, $fullPath);
						imagedestroy($image);
					}

					$mediaSrc = $uploadedPath;
				} else {
					throw new \Exception('No image provided');
				}

				$post = $this->postModel->create($creator, $title, $body, $mediaSrc);
				if ($post) {
					$this->Session->setFlash('success', 'Post created successfully!');
					return $this->Url->redirect('home');
				}
			} catch (\Throwable $th) {
				$this->Session->setFlash('error', 'Failed to create post! ' . $th->getMessage());
				return $this->View->render('post/create');
			}
		}

		// Get available stickers for the view
		$stickersDir = __DIR__ . '/../public/img/stickers';
		$stickers = ImageProcessor::getAvailableStickers($stickersDir);

		$this->View->render('post/create', ['stickers' => $stickers], 'Post Create Page');
	}
}
