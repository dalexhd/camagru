import { Dom, Observer } from "../../";

export default class {
	constructor(name) {
		this.name = name;
		this.dom = new Dom();
		this.observer = new Observer();
		this._state = {};
		this.directivesSelector = 'main';
	}

	set state(state) {
		this._state = state;
		console.info(`[View] ${this.name} view state updated`, this.state);
		this.applyDirectives(state);
	}

	get state() {
		return this._state;
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
		this.applyDirectives(this.state);
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

	applyDirectives(state, selector = 'main') {
		// Apply x-if directives
		let main = document.querySelector(selector);
		if (!main) {
			return;
		}
		const elements = main.querySelectorAll('[x-if]');
		elements.forEach((element) => {
			const condition = element.getAttribute('x-if');
			let evaluated = this.evaluateCondition(condition);
			if (!evaluated) {
				element.style.display = 'none';
			} else {
				element.style.display = '';
			}
		});
		// Apply x-else directives
		const elseElements = main.querySelectorAll('[x-else]');
		elseElements.forEach((element) => {
			const condition = element.getAttribute('x-else');
			let previousCondition = element.previousElementSibling.getAttribute('x-if');
			let evaluated = this.evaluateCondition(previousCondition);
			if (evaluated) {
				element.style.display = 'none';
			} else {
				element.style.display = '';
			}
		});
		// Apply x-else-if directives
		const elseIfElements = main.querySelectorAll('[x-else-if]');
		elseIfElements.forEach((element) => {
			const condition = element.getAttribute('x-else-if');
			let previousCondition = element.previousElementSibling.getAttribute('x-else-if') || element.previousElementSibling.getAttribute('x-if');
			let currentConditionEvaluated = this.evaluateCondition(condition);
			let previousConditionEvaluated = this.evaluateCondition(previousCondition);
			if (!previousConditionEvaluated && currentConditionEvaluated) {
				element.style.display = '';
			} else {
				element.style.display = 'none';
			}
		});
		// {{ }}
		const textElements = main.querySelectorAll('*');
		textElements.forEach((element) => {
			const text = element.innerHTML;
			const matches = text.match(/{{\s*state\.[a-zA-Z0-9]*\s*}}/g);
			if (matches) {
				matches.forEach((match) => {
					const key = match.replace('{{', '').replace('}}', '').replace('state.', '').trim();
					element.innerHTML = element.innerHTML.replace(match, this.state[key]);
				});
			}
		});
		// x-text
		const textElements2 = main.querySelectorAll('[x-text]');
		textElements2.forEach((element) => {
			const key = element.getAttribute('x-text');
			console.info('x-text', key, this.state[key], element, key);
			const value = this.evaluateCondition(key);
			element.innerHTML = value;
		});
	}

	evaluateCondition(expression) {
		// Evaluate the condition within the context of the current state
		try {
			return new Function('state', `return ${expression}`)(this.state);
		} catch (e) {
			console.error('Error evaluating condition:', e);
			return false;
		}
	}
}
