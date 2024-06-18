export default class {
	constructor() {
		this.activeListeners = [];
	}

	// DOM Manipulation
	createElement(tag, attrs = {}, ...children) {
		const element = document.createElement(tag);
		for (const [key, value] of Object.entries(attrs)) {
			element.setAttribute(key, value);
		}
		for (const child of children) {
			if (typeof child === 'string') {
				element.appendChild(document.createTextNode(child));
			} else {
				element.appendChild(child);
			}
		}
		return element;
	}

	append(parent, ...children) {
		for (const child of children) {
			parent.appendChild(child);
		}
	}

	// Event Handling
	on(element, event, handler) {
		element.addEventListener(event, handler);
		this.activeListeners.push({ element, event, handler });
	}

	once(element, event, handler) {
		element.addEventListener(event, handler, { once: true });
	}

	off(element, event, handler) {
		console.info(`[View] Removing listener on ${element.tagName} for event ${event}.`);
		element.removeEventListener(event, handler);
		this.activeListeners = this.activeListeners.filter((listener) => {
			return listener.element !== element || listener.event !== event || listener.callback !== handler;
		});
	}
}
