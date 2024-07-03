import view from "../lib/framework/dom/view"
import authStore from "../store/auth"

export default class extends view {
	constructor() {
		super();
		this.name = 'App';
		this.handleAuthStoreChange = this.handleAuthStoreChange.bind(this);
	}

	async render() {
		const main = document.querySelector('main');
		const authStoreData = authStore.getStore();
		main.innerHTML = `
			<div class="container">
				<h1>${this.name} ${ authStoreData.userName }</h1>
			</div>
		`;
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
