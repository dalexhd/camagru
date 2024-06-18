import view from "../lib/framework/dom/view"
import { Dom, Observer } from "../lib/framework";

export default class extends view {
	constructor() {
		super();
		this.name = 'Test';
		this._state = {
			isLoggedIn: false,
			userName: ''
		};
	}

	async render() {
		const main = document.querySelector('main');
		main.innerHTML = `
			<div class="container">
				<div x-if="state.isLoggedIn">
					<p>Welcome, {{ state.userName }}</p>
				</div>
				<div x-else-if="!state.isLoggedIn">
					<p>Not logged in</p>
				</div>
				<a href="/app/test" x-href="/app/test" class="nav-link px-2 text-secondary">Home</a>
			</div>
		`;
	}

	onMount() {
		console.info('[View] Test view mounted');
		fetch('/api/me')
			.then((response) => response.json())
			.then((data) => {
				this.state = {
					isLoggedIn: data.isLoggedIn,
					userName: data.userName
				};
				console.info('[View] Test view state updated');
			});

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
		console.info('[View] Test view destroyed');
	}
}
