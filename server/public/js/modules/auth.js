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
		// Use event delegation so dynamically added elements also work
		if (this._delegatedAuthHandlerAdded) return; // avoid duplicate listener
		this._delegatedAuthHandlerAdded = true;

		document.addEventListener('click', (e) => {
			// Find closest element that requires auth
			const el = e.target.closest('[data-needs-auth]');
			if (!el) return;

			if (!this.isLoggedIn) {
				e.preventDefault();
				e.stopPropagation();
				location.href = '/login?redirect=' + location.pathname;
			}
		});
	}

	init() {
		console.log('Auth init');
		if (!this.isLoggedIn) {
			this.handleNotLoggedInClicks();
		}
	}
}
