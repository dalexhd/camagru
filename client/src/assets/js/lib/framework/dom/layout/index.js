import { Dom, Observer } from "../../";
import View from "../view";

export default class extends View {
	constructor(name) {
		super();
	}

	async render() {
		const main = document.querySelector('main');
		main.innerHTML = `
			<div class="container">
				<p>Layout</p>
			</div>
		`;
	}

	onBeforeMount() {
		console.info(`[Layout] ${this.name} layout before mounted`);
	}

	onMount() {
		console.info(`[Layout] ${this.name} layout mounted`);
	}

	onBeforeDestroy() {
		console.info(`[Layout] ${this.name} layout before destroyed`);
		this.dom.activeListeners.forEach((listener) => {
			this.dom.off(listener.element, listener.event, listener.handler);
		});
	}

	onDestroy() {
		console.info(`[Layout] ${this.name} layout destroyed`);
	}
}
