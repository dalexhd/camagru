export default class {
	constructor(initialStore = {}) {
		this.store = initialStore;
		this.listeners = [];
	}

	getStore() {
		return this.store;
	}

	subscribe(listener) {
		this.listeners.push(listener);
	}

	unsubscribe(listener) {
		this.listeners = this.listeners.filter(l => l !== listener);
	}

	updateStore(newStore) {
		let updated = false;
		Object.keys(newStore).forEach(key => {
			if (this.store[key] !== newStore[key]) {
				updated = true;
			}
		});
		if (!updated) return;
		const updatedProperties = Object.keys(newStore).filter(key => this.store[key] !== newStore[key]);
		this.store = { ...this.store, ...newStore };
		this.notify(updatedProperties);
	}

	notify(updatedProperties = []) {
		this.listeners.forEach(listener => listener(this.store, updatedProperties));
	}
}
