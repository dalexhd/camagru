import Layout from "../lib/framework/dom/layout";

// Components
import Footer from "../components/Footer";
import Header from "../components/Header";
import Main from "../components/Main";

// Store
import authStore from "../store/auth";

export default class extends Layout {
	constructor() {
		super();
		this.name = 'Unauthenticated';
		this.handleAuthStoreChange = this.handleAuthStoreChange.bind(this);
		this.logout = this.logout.bind(this);
		this.login = this.login.bind(this);
	}

	async render() {
		const app = document.getElementById('app');
		this.renderHeader();
		this.renderMain();
		this.renderFooter();
		this.listenHeaderEvents();
	}

	renderHeader() {
		const header = document.querySelector('header');
		const headerComponentHTML = Header();
		debugger;
		if (header === null) {
			document.getElementById('app').prepend(Header());
		} else if (header && header.innerHTML !== headerComponentHTML.innerHTML) {
			header.replaceWith(Header());
		}
	}

	renderMain() {
		const main = document.querySelector('main');
		if (main === null) {
			document.getElementById('app').appendChild(Main());
		}
		const mainContent = document.querySelector('main');
		mainContent.innerHTML = `
			<router-view></router-view>
		`;
	}

	renderFooter() {
		const footer = document.querySelector('footer');
		const footerComponentHTML = Footer();
		if (footer === null) {
			document.getElementById('app').appendChild(Footer());
		} else if (footer && footer.innerHTML !== footerComponentHTML.innerHTML) {
			footer.replaceWith(Footer());
		}
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
		const { isLoggedIn } = newStore;
		this.renderHeader();
		if (isLoggedIn) {
			this.renderMain();
		}
		this.listenHeaderEvents();
	}

	// Event listeners
	listenHeaderEvents() {
		const header = document.querySelector('header');
		// Logout button
		const logoutButton = header?.querySelector('.logout');
		if (logoutButton) {
			logoutButton.addEventListener('click', this.logout);
		}

		// Login button
		const loginButton = header?.querySelector('.login');
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
