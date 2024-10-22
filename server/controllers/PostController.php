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
		$this->View->render('post/create', ['message' => 'Hello, World!'], 'Post Create Page');
	}
}
