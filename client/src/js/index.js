import '../scss/style.scss';

import Router from "./router";
import AuthStore from "./store/auth";

const setupLayout = async (AuthenticatedLayout) => {
	const app = document.getElementById('app');
}

const setupRoutes = async () => {
	import("./config").then(({ routes, title }) => {
		const router = new Router(routes, title);
		router.init();
	});
}

const setupStore = async () => {
	await AuthStore.initializeAuthStore();
	let { isLoggedIn } = AuthStore.getStore();
	if (isLoggedIn) {
		console.info('[App] User is logged in');
	}
}

document.addEventListener('DOMContentLoaded', async () => {
	await setupStore();
	await setupLayout();
	setupRoutes();
});
