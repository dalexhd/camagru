export default class {
	constructor(routes, prefix = '') {
		this.routes = routes; // Array of routes
		this.prefix = prefix;
		this.init();
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
		const currentPath = window.location.pathname;
		let match = this.routes.find((route) => route.path === currentPath);
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
		this.currentRoute.onBeforeDestroy();
		this.currentRoute.onDestroy();
	}

	afterNavigate() {
		console.info('[Router] After navigate');
		this.currentRoute.onBeforeMount();
		this.currentRoute.onMount();
	}

	navigate(path) {
		let match = this.routes.find((route) => route.path === path);
		if (match) {
			if (this.currentRoute)
				this.beforeNavigate();
			this.currentRoute = new match.view();
			this.currentRoute.render();
			this.changeRoute(match);
			this.afterNavigate();
		} else {
			console.error('[Router] Route not found');
		}
	}
}
