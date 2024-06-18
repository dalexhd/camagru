export default class {
	constructor() {
		this.isLoggedIn = false;
		this.userName = '';
	}

	get isAuthenticated() {
		return this.isLoggedIn;
	}

	async checkAuth() {
		const response = await fetch('/api/me');
		const data = await response.json();
		this.isLoggedIn = data.isLoggedIn;
		this.userName = data.userName;
	}
}
