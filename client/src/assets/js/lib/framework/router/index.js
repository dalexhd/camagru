export default class {
	constructor(routes, prefix = '') {
		this.routes = routes; // Array of routes
		this.prefix = prefix;
	}

	get defaultRoute() {
		return this.routes.find((route) => route.default);
	}

	get defaultAppRoute() {
		// This is a hack to get the default app route
		return this.routes.find((route) => route.path === '/app');
	}

	init() {
		document.addEventListener('click', (e) => {
			if (e.target.matches('[x-href]')) {
				e.preventDefault();
				let path = e.target.getAttribute('x-href');
				console.info(`[Router] Navigating to url: ${path}`);
				this.navigate(path, false); // Use navigate with pushState = true
			}
		});

		window.addEventListener('popstate', () => {
			const currentPath = window.location.pathname;
			console.info('[Router] Current path', currentPath);
			let match = this.getMatchedRoute(currentPath);
			console.info('[Router] Matched route', match);
			if (match) {
				console.info(`[Router] Loading url: ${match.path}`);
				this.loadRoute(match, false); // Load route without changing history state
			} else {
				console.error('[Router] Route not found');
			}
		});

		const currentPath = window.location.pathname;
		let match = this.getMatchedRoute(currentPath);
		console.info('[Router] Current path', match);
		if (match) {
			console.info(`[Router] Loading url: ${match.path}`);
			this.loadRoute(match, false); // Load route without changing history state
		} else {
			let defaultRoute = this.routes.find((route) => route.default);
			if (defaultRoute) {
				console.info(`[Router] Navigating to default url: ${defaultRoute.path}`);
				this.navigate(defaultRoute.path, true); // Use navigate with replaceState = true
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
		document.title = `${this.prefix} | ${route.name}`;
		window.history.pushState({}, route.name, window.location.origin + route.path);
	}

	async loadRoute(route, pushState = true) {
		if (this.currentRoute) this.beforeNavigate();
		this.currentLayout = new route.layout();
		console.info('[Router] Current layout', this.currentLayout);
		await this.currentLayout.render();
		this.currentRoute = new route.view();
		await this.currentRoute.render();
		if (pushState) {
			this.changeRoute(route);
		}
		this.afterNavigate();
	}

	navigate(path, replace = false) {
		let match = this.getMatchedRoute(path);
		if (match) {
			if (replace) {
				window.history.replaceState({}, match.name, window.location.origin + match.path);
				this.loadRoute(match, false); // Load route without changing history state
			} else {
				this.loadRoute(match);
			}
		} else {
			console.error('[Router] Route not found');
		}
	}

	beforeNavigate() {
		console.info('[Router] Before navigate');
		const app = document.getElementById('app');
		if (this.currentLayout) {
			this.currentLayout.onBeforeDestroy();
			this.currentLayout.onDestroy();
		}
		if (this.currentRoute) {
			this.currentRoute.onBeforeDestroy();
			this.currentRoute.onDestroy();
		}
		app.innerHTML = '';
	}

	afterNavigate() {
		console.info('[Router] After navigate');
		if (this.currentLayout) {
			this.currentLayout.onBeforeMount();
			this.currentLayout.onMount();
		}
		if (this.currentRoute) {
			this.currentRoute.onBeforeMount();
			this.currentRoute.onMount();
		}
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
		return null;
	}
}
