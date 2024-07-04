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
			this.updateStore({
				isLoggedIn: data.isLoggedIn,
				userName: data.userName
			});
		} catch (error) {
			console.error('Error initializing auth store:', error);
		}
	}

	login(userName) {
		this.updateStore({
			isLoggedIn: true,
			userName: userName
		});
	}

	logout() {
		this.updateStore({
			isLoggedIn: false,
			userName: ''
		});
	}
}

const authStore = new AuthStore();
export default authStore;
