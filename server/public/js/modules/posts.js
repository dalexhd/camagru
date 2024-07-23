import CamagruModule from './module.js';
import PostModule from './post.js';

export default class extends CamagruModule {
	get currentPageIndex() {
		return Math.floor(this._currentPostIndex / this._pageSize);
	}

	get pageSize() {
		return this._pageSize;
	}

	get currentPost() {
		return this.posts[this._currentPostIndex];
	}

	set currentPost(post) {
		this._currentPostIndex = this.posts.indexOf(post);
	}

	get posts() {
		return this._posts;
	}

	set posts(posts) {
		this._posts = posts;
	}

	constructor() {
		super();
		this._posts = [];
		this._pageSize = 5;
		this._currentPostIndex = 5;
		console.log('Posts constructor');
	}

	async loadPosts() {
		const posts = await fetch(`/api/posts/${this.currentPageIndex}/${this.pageSize}`);
		const postsJson = await posts.json();
		this.posts = [
			...this.posts,
			...postsJson.map((post) => new PostModule(post)),
		];
	}

	onPostScrollEnd(selector) {
		var observer = new IntersectionObserver(function (entries) {
			if (!entries[0].isIntersecting) {
				selector.classList.remove('is-active');
				document.querySelector(`.post-comments[data-id="${selector.dataset.id}"]`).classList.remove('is-active');
			}
			else {
				selector.classList.add('is-active');
				document.querySelector(`.post-comments[data-id="${selector.dataset.id}"]`).classList.add('is-active');
			}
		}, {
			rootMargin: '-60px',
			threshold: 0.5
		});

		observer.observe(selector);
	}

	async init() {
		await this.loadPosts();
		document.querySelector('#post-container-wrapper').innerHTML = this.posts.map((post) => post.html.post).join('');
		document.querySelector('#post-container-wrapper').querySelector('.post-container').classList.add('is-active');
		document.querySelector('#post-comments-wrapper').innerHTML = this.posts.map((post) => post.html.comments).join('');
		document.querySelector('#post-comments-wrapper').querySelector('.post-comments').classList.add('is-active');
		document.querySelectorAll('.post-container').forEach((postContainer) => {
			this.onPostScrollEnd(postContainer);
		});
	}
}
