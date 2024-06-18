import view from "../lib/framework/dom/view"
import { Dom, Observer } from "../lib/framework";

export default class extends view {
	constructor() {
		super();
		this.name = 'Test';
	}

	async render() {
		const main = document.querySelector('main');
		main.innerHTML = `
			<div class="container">
				<h1>${this.name}</h1>
				<p>Test content goes here</p>
			</div>
		`;
	}

	onMount() {
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
		console.info('[View] Test view destroyed');
	}
}
