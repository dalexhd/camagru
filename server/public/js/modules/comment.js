import CamagruModule from './module.js';

export default class extends CamagruModule {
	constructor() {
		super();
		console.log('Comment constructor');
	}

	loadCommentsToggle() {
		document.addEventListener('click', (e) => {
			const closestOpenComments = e.target.closest('.open-comments');
			if (closestOpenComments) {
				const $postContainer = closestOpenComments.closest('.post-container');
				const $postId = $postContainer.dataset.id;
				const $commentsContainer = document.querySelector(`.post-comments[data-id="${$postId}"]`);
				const $commentsContainerWrapper = document.querySelector('#post-comments-wrapper');
				$commentsContainer.classList.add('is-active');
				$commentsContainerWrapper.classList.add('is-active');
			}

			const closestCloseComments = e.target.closest('.close-comments');
			if (closestCloseComments) {
				const $commentsContainer = closestCloseComments.closest('.post-comments');
				const $commentsContainerWrapper = document.querySelector('#post-comments-wrapper');
				$commentsContainer.classList.remove('is-active');
				$commentsContainerWrapper.classList.remove('is-active');
				$commentsContainerWrapper.classList.add('is-closing');
				setTimeout(() => {
					$commentsContainerWrapper.classList.remove('is-closing');
				}, 200);
			}

			const closeComments = () => {
				document.querySelector('#post-comments-wrapper').classList.remove('is-active');
				document.querySelector('#post-comments-wrapper').classList.add('is-closing');
				document.querySelector('.post-comments.is-active').classList.remove('is-active');
				setTimeout(() => {
					document.querySelector('#post-comments-wrapper').classList.remove('is-closing');
				}, 200);
			}
			if (!e.target.closest('#post-comments-wrapper') && !e.target.closest('.open-comments') && document.querySelector('#post-comments-wrapper').classList.contains('is-active')) {
				closeComments();
			}
		});
	}

	init() {
		console.log('Comment init');
		this.loadCommentsToggle();
	}
}

