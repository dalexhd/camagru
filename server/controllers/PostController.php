<?php

use core\Controller;
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
			$title = $_POST['title'];
			$body = $_POST['body'];
			$mediaSrc = null;
			try {
				$file = $_FILES['media'];
				$mediaSrc = $this->File->upload($file, 'img/uploads');
				$post = $this->postModel->create($creator, $title, $body, $mediaSrc);
				if ($post) {
					$this->Session->setFlash('Post created successfully!', 'success');
					return $this->Url->redirect('home');
				}
			} catch (\Throwable $th) {
				$this->Session->setFlash('Failed to create post!' . $th->getMessage(), 'danger');
				echo $th->getTraceAsString();
				return $this->View->render('post/create');
			}
		}
		$this->View->render('post/create', ['message' => 'Hello, World!'], 'Post Create Page');
	}
}
