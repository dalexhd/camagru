class Observer {
	constructor() {
		this.observers = {};
	}

	on(event, callback) {
		if (!this.observers[event]) {
			this.observers[event] = [];
		}
		this.observers[event].push(callback);
	}

	emit(event, data) {
		if (this.observers[event]) {
			this.observers[event].forEach((callback) => callback(data));
		}
	}

	off(event, callback) {
		if (this.observers[event]) {
			this.observers[event] = this.observers[event].filter(
				(observer) => observer !== callback
			);
		}
	}
}

const observer = new Observer();

export default observer;
