import CamagruModule from './module.js';
import PostModule from './post.js';

export default class extends CamagruModule {
	get isLoggedIn() {
		return this._isLoggedIn;
	}
	
	constructor(status = false) {
		super();
		this._isLoggedIn = status;
		console.log('Auth constructor');
	}

	handleNotLoggedInClicks() {
		const elements = document.querySelectorAll('[data-needs-auth]');
		elements.forEach((element) => {
			element.addEventListener('click', (e) => {
				if (!this.isLoggedIn) {
					e.preventDefault();
					e.stopPropagation();
					location.href = '/login?redirect=' + location.pathname;
				}
			});
		});
	}

	init() {
		console.log('Auth init');
		if (!this.isLoggedIn) {
			this.handleNotLoggedInClicks();
		}
	}
}
