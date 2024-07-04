import { Dom, Observer } from "../../";

export default class {
	constructor(name) {
		this.name = name;
		this.dom = new Dom();
		this.observer = new Observer();
	}

	async render() {
		const main = document.querySelector('main');
		main.innerHTML = `
			<div class="container">
				<h1>${this.name}</h1>
				<p>Content goes here</p>
			</div>
		`;
	}

	onBeforeMount() {
		console.info(`[View] ${this.name} view before mounted`);
	}

	onMount() {
		console.info(`[View] ${this.name} view mounted`);
	}

	onBeforeDestroy() {
		console.info(`[View] ${this.name} view before destroyed`);
		this.dom.activeListeners.forEach((listener) => {
			this.dom.off(listener.element, listener.event, listener.handler);
		});
	}

	onDestroy() {
		console.info(`[View] ${this.name} view destroyed`);
	}
}
