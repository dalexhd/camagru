import { Dom, Observer } from "../..";
import { bindDirectives } from "../directives";

export default class {
	constructor(name) {
		this.name = name;
		this.dom = new Dom();
		this.observer = new Observer();
		this.data = this.data();
		this.bindDirectives = bindDirectives;
	}

	data() {
		return {};
	}

	render(template) {
		const main = document.querySelector('main');
		main.innerHTML = template;
		this.bindDirectives(this.data);
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
