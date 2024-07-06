import view from "../lib/framework/dom/view"
import authStore from "../store/auth"

export default class extends view {
	constructor() {
		super();
		this.name = 'App';
		this.handleAuthStoreChange = this.handleAuthStoreChange.bind(this);
	}

	// Override the data method to provide the initial data state for this view
	data() {
		const authStoreData = authStore.getStore();
		return {
			userName: authStoreData.userName,
			isLoggedIn: authStoreData.isLoggedIn,
			form: {
				userName: 'asdas',
				email: '',
			}
		};
	}

	async render() {
		const template = `
			<div class="container">
				<h1>${this.name} {{ form.email }}</h1>
			</div>
			<form>
				<div>
					<label for="userName">Name:</label>
					<input id="userName" type="text" x-model="form.userName">
				</div>
				<div>
				<div x-if="form.userName.length > 8">
					<p>Name is too long</p>
				</div>
				<label for="email">Email:</label>
					<input id="email" type="email" x-model="form.email">
				</div>
				<div>
					<p>Global User: <span x-text="form.userName"></span></p>
				</div>
		  	</form>
		`;
		super.render(template);
	}

	onMount() {
		authStore.subscribe(this.handleAuthStoreChange);
		console.info('[View] App view mounted');
	}

	onDestroy() {
		authStore.unsubscribe(this.handleAuthStoreChange);
		console.info('[View] App view destroyed');
	}

	handleAuthStoreChange(newStore) {
		this.render();
	}
}
