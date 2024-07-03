import Router from '../lib/framework/router';
import authStore from '../store/auth';

export default class extends Router {
	constructor(routes, prefix = '') {
		super(routes, prefix);
		// Bind the store change handlers
		this.handleAuthStoreChange = this.handleAuthStoreChange.bind(this);
		authStore.subscribe(this.handleAuthStoreChange);
	}

	async navigate(path) {
		let match = this.getMatchedRoute(path);
		const { isLoggedIn } = authStore.getStore();
		console.log("before navigate", this.currentRoute)
		if (match) {
			if (this.currentRoute)
				this.beforeNavigate();
			if (isLoggedIn && match.auth) {
				console.info('[Router] Route requires authentication');
			} else if (!isLoggedIn && match.auth) {
				console.error('[Router] Route requires authentication');
				this.navigate(this.defaultRoute.path);
				return;
			}
			this.changeRoute(match);
			this.currentLayout = new match.layout();
			console.info('[Router] Current layout', this.currentLayout);
			await this.currentLayout.render();
			this.currentRoute = new match.view();
			await this.currentRoute.render();
			this.afterNavigate();
		} else {
			console.error('[Router] Route not found');
		}
	}

	changeTitle(title) {
		document.title = `${this.prefix} | ${title}`;
	}

	loadRoute(route, pushState = true) {
		const { isLoggedIn } = authStore.getStore();
		if (this.currentRoute) this.beforeNavigate();
		if (isLoggedIn && route.auth) {
			console.info('[Router] Route requires authentication');
		} else if (!isLoggedIn && route.auth) {
			console.error('[Router] Route requires authentication');
			this.navigate(this.defaultRoute.path);
			return;
		}
		this.currentLayout = new route.layout();
		console.info('[Router] Current layout', this.currentLayout);
		this.currentLayout.render();
		this.currentRoute = new route.view();
		this.currentRoute.render();
		if (pushState) {
			this.changeRoute(route);
		} else {
			this.changeTitle(route.name);
		}
		this.afterNavigate();
	}

	handleAuthStoreChange(store, updatedProperties) {
		const path = window.location.pathname;
		let match = this.getMatchedRoute(path);
		if (!store.isLoggedIn && match.auth) {
			console.error('[Router] Route requires authentication');
			this.navigate(this.defaultRoute.path);
			return;
		} else {
			console.info('[Router] Route does not require authentication');
		}
		console.log("updatedProperties", updatedProperties, store.isLoggedIn, this.defaultAppRoute)
		if (store.isLoggedIn && updatedProperties.includes('isLoggedIn')) {
			this.navigate(this.defaultAppRoute.path);
		} else if (!store.isLoggedIn && updatedProperties.includes('isLoggedIn')) {
			this.navigate(this.defaultRoute.path);
		}
	}
}
