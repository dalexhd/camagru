import Router from "./lib/framework/router";

// Utils
// - Auth
import Auth from "./lib/utils/auth";

// Config
import { routes, title } from "./config";

const setupLayout = (AuthenticatedLayout) => {
	const app = document.getElementById('app');

}

const setupRoutes = async () => {
	const router = new Router(routes, title);
	await router.checkAuth();
	router.init();
}

document.addEventListener('DOMContentLoaded', () => {
	setupLayout();
	setupRoutes();
});
