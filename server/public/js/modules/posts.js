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
		this.updateUrlForCurrentPost();
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
		this._currentPostIndex = 0;
		this._isLoading = false;
		this._hasMore = true;
		this._currentPage = 0;

		console.log('Posts constructor');
	}

	updateUrlForCurrentPost() {
		const current = this.currentPost;
		if (!current || !current.post || !current.post.id) {
			return;
		}

		const url = `/post/${current.post.id}`;
		if (window.location.pathname === url) {
			return;
		}

		window.history.replaceState({}, '', url);
	}

	async loadPosts() {
		try {
			if (this._isLoading || !this._hasMore) {
				return;
			}

			this._isLoading = true;

			const response = await fetch(`/api/posts/${this._currentPage}/${this.pageSize}`);
			if (!response.ok) {
				const text = await response.text();
				console.error('Failed to load posts:', response.status, text);
				throw new Error('Failed to load posts');
			}

			const postsJson = await response.json();
			if (!Array.isArray(postsJson) || postsJson.length === 0) {
				this._hasMore = false;
				this._isLoading = false;
				return;
			}

			this.posts = [
				...this.posts,
				...postsJson.map((post) => new PostModule(post)),
			];

			this._currentPage += 1;
			this._isLoading = false;
		} catch (error) {
			console.error('Error loading posts:', error);
			this._isLoading = false;
			throw error;
		}
	}

	setupInfiniteScroll() {
		const wrapper = document.querySelector('#post-container-wrapper');
		if (!wrapper) {
			return;
		}

		let sentinel = wrapper.querySelector('.post-sentinel');
		if (!sentinel) {
			sentinel = document.createElement('div');
			sentinel.className = 'post-sentinel';
			wrapper.appendChild(sentinel);
		}

		const observer = new IntersectionObserver(async (entries) => {
			if (!entries[0].isIntersecting) {
				return;
			}

			observer.unobserve(sentinel);

			const previousLength = this.posts.length;
			await this.loadPosts();
			const newPosts = this.posts.slice(previousLength);
			if (newPosts.length === 0) {
				return;
			}

			const commentsWrapper = document.querySelector('#post-comments-wrapper');

			newPosts.forEach((postModule) => {
				const postElement = this._createElementFromHTML(postModule.html.post);
				wrapper.insertBefore(postElement, sentinel);

				const commentsElement = this._createElementFromHTML(postModule.html.comments);
				commentsWrapper.appendChild(commentsElement);
				this.onPostScrollEnd(postElement);
			});

			this.onPostShareEvent();

			observer.observe(sentinel);
		}, {
			rootMargin: '200px',
			threshold: 0.1,
		});

		observer.observe(sentinel);
	}

	_createElementFromHTML(html) {
		const temp = document.createElement('div');
		temp.innerHTML = html.trim();
		return temp.firstElementChild;
	}

	onPostScrollEnd(postElement) {
		const observer = new IntersectionObserver((entries) => {
			const isVisible = entries[0].isIntersecting;
			const postId = postElement.dataset.id;
			const commentsElement = document.querySelector(`.post-comments[data-id="${postId}"]`);

			if (!commentsElement) {
				return;
			}

			if (!isVisible) {
				postElement.classList.remove('is-active');
				commentsElement.classList.remove('is-active');
				return;
			}

			postElement.classList.add('is-active');
			commentsElement.classList.add('is-active');

			if (window.CamagruPostsInstance) {
				const instance = window.CamagruPostsInstance;
				const ids = instance.posts.map((p) => p.post.id);
				const index = ids.indexOf(parseInt(postId, 10));

				if (index !== -1) {
					instance._currentPostIndex = index;
					instance.updateUrlForCurrentPost();
				}
			}
		}, {
			rootMargin: '-60px',
			threshold: 0.5,
		});

		observer.observe(postElement);
	}

	onPostShareEvent() {
		document.querySelectorAll('#post-container-wrapper .post-share').forEach((shareButton) => {
			shareButton.addEventListener('click', (event) => {
				const postElement = event.target.closest('.post-container');
				const postId = postElement?.dataset.id;

				if (!postId) {
					return;
				}

				const post = this.posts.map((p) => p.post).find((p) => p.id == postId);
				if (!post) {
					return;
				}

				const shareUrl = `${window.location.origin}/post/${post.id}`;
				const shareText = `Check out this post on Camagru: ${shareUrl}`;

				if (navigator.share) {
					navigator.share({
						title: 'Camagru',
						text: shareText,
						url: shareUrl,
					});
				} else if (navigator.clipboard && navigator.clipboard.writeText) {
					navigator.clipboard.writeText(shareUrl).then(() => {
						alert('Post URL copied to clipboard');
					});
				}
			});
		});
	}

	async init() {
		const match = window.location.pathname.match(/\/post\/(\d+)/);
		const targetPostId = match ? parseInt(match[1], 10) : null;
		let foundIndex = -1;

		if (targetPostId !== null) {
			while (this._hasMore && foundIndex === -1) {
				await this.loadPosts();
				if (this.posts.length === 0) {
					break;
				}
				foundIndex = this.posts.map((p) => p.post.id).indexOf(targetPostId);
			}
		} else {
			await this.loadPosts();
		}

		if (this.posts.length === 0) {
			const container = document.querySelector('#post-container-wrapper');
			if (container) {
				container.innerHTML = '<p>No posts found</p>';
			}
			return;
		}

		const wrapper = document.querySelector('#post-container-wrapper');
		const commentsWrapper = document.querySelector('#post-comments-wrapper');

		if (!wrapper || !commentsWrapper) {
			return;
		}

		wrapper.innerHTML = this.posts.map((post) => post.html.post).join('');

		const sentinel = document.createElement('div');
		sentinel.className = 'post-sentinel';
		wrapper.appendChild(sentinel);

		commentsWrapper.innerHTML = this.posts.map((post) => post.html.comments).join('');

		// Decide which post should start as active
		let initialIndex = 0;
		if (foundIndex !== -1) {
			initialIndex = foundIndex;
		}
		this._currentPostIndex = initialIndex;

		const postContainers = document.querySelectorAll('#post-container-wrapper .post-container');
		const commentsContainers = document.querySelectorAll('#post-comments-wrapper .post-comments');

		// Mark the initial post and its comments as active
		if (postContainers[initialIndex]) {
			postContainers[initialIndex].classList.add('is-active');
			postContainers[initialIndex].scrollIntoView({ behavior: 'auto', block: 'center' });
		}
		if (commentsContainers[initialIndex]) {
			commentsContainers[initialIndex].classList.add('is-active');
		}

		// Attach scroll observers to all posts so they can become active
		postContainers.forEach((postContainer) => {
			this.onPostScrollEnd(postContainer);
		});

		// Wire up share buttons for the initial batch of posts
		this.onPostShareEvent();

		// Expose instance so IntersectionObserver callback can update URL
		window.CamagruPostsInstance = this;

		// Finally, enable infinite scrolling for subsequent pages
		this.setupInfiniteScroll();
	}
}
