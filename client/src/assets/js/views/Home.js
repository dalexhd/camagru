import view from "../lib/framework/dom/view"
import authStore from "../store/auth"

export default class extends view {
	constructor() {
		super();
		this.name = 'Home';
	}

	async render() {
		const main = document.querySelector('main');
		const authStoreData = authStore.getStore();
		main.innerHTML = `
			<div class="container">
				${ (authStoreData.isLoggedIn) ? `<h1>${this.name} ${ authStoreData.userName }</h1>` : `<h1>${this.name}</h1>` }
			</div>
		`;
	}

	onMount() {
		console.info('[View] Home view mounted');
	}

	onDestroy() {
		console.info('[View] Home view destroyed');
	}
}
