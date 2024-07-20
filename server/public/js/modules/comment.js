import CamagruModule from './module.js';

export default class extends CamagruModule {
	constructor() {
		super();
		console.log('Comment constructor');
	}

	loadCommentsToggle() {
		const $openComments = document.querySelectorAll('.open-comments');
		const $closeComments = document.querySelectorAll('.close-comments');
		const $commentsWrapper = document.querySelector('#post-comments-wrapper');
		const $commentInput = $commentsWrapper.querySelector('.input');

		$openComments.forEach(($openComment) => {
			$openComment.addEventListener('click', () => {
				$commentsWrapper.classList.add('is-active');
			});
		});

		const closeComments = () => {
			$commentsWrapper.classList.remove('is-active');
			$commentsWrapper.classList.add('is-closing');
			setTimeout(() => {
				$commentsWrapper.classList.remove('is-closing');
				$commentInput.value = '';
			}, 200);
		}

		$closeComments.forEach(($closeComment) => {
			$closeComment.addEventListener('click', () => {
				closeComments();
			});
		});

		// Close comments when clicking outside
		document.addEventListener('click', (e) => {
			if (!e.target.closest('#post-comments-wrapper') && !e.target.closest('.open-comments') && $commentsWrapper.classList.contains('is-active')) {
				closeComments();
			}
		});
	}

	init() {
		console.log('Comment init');

		this.loadCommentsToggle();
	}
}
