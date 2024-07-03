import view from "../lib/framework/dom/view"
import authStore from "../store/auth";

export default class extends view {
	constructor() {
		super();
		this.name = 'Test';
		this.handleAuthStoreChange = this.handleAuthStoreChange.bind(this);
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
		authStore.subscribe(this.handleAuthStoreChange);
		console.info('[View] Test view mounted');
		fetch('/api/data')
			.then((response) => response.json())
			.then((data) => {
				let main = document.querySelector('main > div.container');

				const message = this.dom.createElement('p', {}, `Data received from server: ${data.message}`);
				this.dom.append(main, message);

				const button = this.dom.createElement('button', {}, 'Click me');
				this.dom.append(main, button);

				this.dom.on(button, 'click', () => {
					this.observer.emit('button-clicked', 'Button clicked');
				});

				this.observer.on('button-clicked', (data) => {
					const message = this.dom.createElement('p', {}, data);
					this.dom.append(main, message);
				});
			});
	}

	onDestroy() {
		authStore.unsubscribe(this.handleAuthStoreChange);
		console.info('[View] Test view destroyed');
	}

	handleAuthStoreChange(newStore) {
		this.render();
	}
}
