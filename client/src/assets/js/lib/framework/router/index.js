import auth from "../../utils/auth";
import Auth from "../../utils/auth";

export default class extends Auth {
	constructor(routes, prefix = '') {
		super();
		this.routes = routes; // Array of routes
		this.prefix = prefix;
	}

	init() {
		document.addEventListener('click', (e) => {
			if (e.target.matches('[x-href]')) {
				e.preventDefault();
				let path = e.target.getAttribute('x-href');
				console.info(`[Router] Navigating to url: ${path}`);
				this.navigate(path);
			}
		});
		window.addEventListener('popstate', () => {
			const currentPath = window.location.pathname;
			console.info('[Router] Current path', currentPath);
			let match = this.getMatchedRoute(currentPath);
			console.info('[Router] Matched route', match);
			if (match) {
				console.info(`[Router] Navigating to url: ${match.path}`);
				this.navigate(match.path);
			} else {
				console.error('[Router] Route not found');
			}
		});
		const currentPath = window.location.pathname;
		let match = this.getMatchedRoute(currentPath);
		console.info('[Router] Current path', match);
		if (match) {
			console.info(`[Router] Navigating to url: ${match.path}`);
			this.navigate(match.path);
		} else {
			let defaultRoute = this.routes.find((route) => route.default);
			if (defaultRoute) {
				console.info(`[Router] Navigating to default url: ${defaultRoute.path}`);
				this.navigate(defaultRoute.path);
			} else {
				console.error('[Router] No default route found');
			}
		}

	}

	addRoute(path, view) {
		this.routes.push({
			path,
			view
		});
	}

	changeRoute(route) {
		window.history.pushState({}, route.path, window.location.origin + route.path);
		document.title = `${this.prefix} | ${route.name}`;
	}

	beforeNavigate() {
		console.info('[Router] Before navigate');
		const app = document.getElementById('app');
		app.innerHTML = '';
		this.currentRoute.onBeforeDestroy();
		this.currentRoute.onDestroy();
	}

	afterNavigate() {
		console.info('[Router] After navigate');
		this.currentRoute.onBeforeMount();
		this.currentRoute.onMount();
	}

	getMatchedRoute(path) {
		const routes = this.routes;
		const matchedRoute = routes.find((route) => route.path === path);
		if (matchedRoute) {
			return matchedRoute;
		}
		let matchedChildRoute = null;
		for (let i = 0; i < routes.length; i++) {
			const route = routes[i];
			if (route.children) {
				matchedChildRoute = route.children.find((child) => route.path + child.path === path);
				console.info('[Router] Matched child route', matchedChildRoute);
				if (matchedChildRoute) {
					return {
						...matchedChildRoute,
						path: route.path + matchedChildRoute.path,
						layout: route.layout,
						auth: route.auth || matchedChildRoute.auth,
						view: matchedChildRoute.view
					};
				}
			}
		}
	}

	navigate(path) {
		let match = this.getMatchedRoute(path);
		const defaultRoute = this.routes.find((route) => route.default);
		if (match) {
			if (this.currentRoute)
				this.beforeNavigate();
			if (this.isAuthenticated && match.auth) {
				console.info('[Router] Route requires authentication');
			} else if (!this.isAuthenticated && match.auth) {
				console.error('[Router] Route requires authentication');
				this.navigate(defaultRoute.path);
				return;
			}
			this.currentLayout = new match.layout();
			console.info('[Router] Current layout', this.currentLayout);
			this.currentLayout.render();
			this.currentLayout.state = {
				...this.currentLayout.state,
				isLoggedIn: this.isAuthenticated,
				userName: this.userName
			}
			this.currentRoute = new match.view();
			this.currentRoute.state = {
				...this.currentRoute.state,
				isLoggedIn: this.isAuthenticated,
				userName: this.userName
			}
			this.currentRoute.render();
			this.changeRoute(match);
			this.afterNavigate();

			// setTimeout(() => {
			// 	this.currentLayout.state = {
			// 		...this.currentLayout.state,
			// 		isLoggedIn: true,
			// 		userName:'asdasdasdas'
			// 	}
			// }, 5000);
		} else {
			console.error('[Router] Route not found');
		}
	}
}
