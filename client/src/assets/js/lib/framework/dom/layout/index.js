import { Dom, Observer } from "../../";
import View from "../view";

export default class extends View {
	constructor(name) {
		super();
	}

	set state(state) {
		this._state = state;
		console.info(`[Layout] ${this.name} layout state updated`, this.state);
		this.applyDirectives(this.state, 'header');
		this.applyDirectives(this.state, 'footer');
	}

	get state() {
		return this._state;
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
		this.applyDirectives(this.state);
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
