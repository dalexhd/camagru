import { Store } from '../lib/framework';

class AuthStore extends Store {
	constructor() {
		super({
			isLoggedIn: false,
			userName: ''
		});
	}

	async initializeAuthStore() {
		try {
			const response = await fetch('/api/me');
			const data = await response.json();
			this.setStore({
				isLoggedIn: data.isLoggedIn,
				userName: data.userName
			});
		} catch (error) {
			console.error('Error initializing auth store:', error);
		}
	}

	login(userName) {
		this.setStore({
			isLoggedIn: true,
			userName: userName
		});
	}

	logout() {
		this.setStore({
			isLoggedIn: false,
			userName: ''
		});
	}
}

const authStore = new AuthStore();
export default authStore;
