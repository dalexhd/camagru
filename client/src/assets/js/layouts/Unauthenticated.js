import Layout from "../lib/framework/dom/layout"

// Components
import Header from "../components/Header";
import Main from "../components/Main";

// Store
import authStore from "../store/auth";
import router from "../router";
export default class extends Layout {
	constructor() {
		super();
		this.name = 'Unauthenticated';
		this.handleAuthStoreChange = this.handleAuthStoreChange.bind(this);
	}

	async render() {
		const app = document.getElementById('app');
		app.innerHTML = '';
		app.appendChild(Header());
		app.appendChild(Main());
		const main = document.querySelector('main');
		main.innerHTML = `
			<router-view></router-view>
		`;
		this.listenHeaderEvents();
	}

	onMount() {
		authStore.subscribe(this.handleAuthStoreChange);
		console.info(`[Layout] ${this.name} layout mounted`);
	}

	onDestroy() {
		authStore.unsubscribe(this.handleAuthStoreChange);
		this.removeHeaderEvents();
		console.info(`[Layout] ${this.name} layout destroyed`);
	}

	handleAuthStoreChange(newStore) {
		this.render();
	}

	// Event listeners
	listenHeaderEvents() {
		const header = document.querySelector('header');
		// Logout button
		const logoutButton = header.querySelector('.logout');
		if (logoutButton) {
			logoutButton.addEventListener('click', this.logout);
		}

		// Login button
		const loginButton = header.querySelector('.login');
		if (loginButton) {
			loginButton.addEventListener('click', this.login);
		}
	}

	removeHeaderEvents() {
		const header = document.querySelector('header');
		// Logout button
		const logoutButton = header?.querySelector('.logout');
		if (logoutButton) {
			logoutButton.removeEventListener('click', this.logout);
		}

		// Login button
		const loginButton = header?.querySelector('.login');
		if (loginButton) {
			loginButton.removeEventListener('click', this.login);
		}
	}

	// Actions
	async logout() {
		authStore.updateStore({ isLoggedIn: false });
	}

	async login() {
		authStore.updateStore({ isLoggedIn: true });
	}
}
