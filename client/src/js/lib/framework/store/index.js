// BaseStore.js
// A base class for creating reactive stores

class BaseStore {
	constructor(initialStore = {}) {
	  this.store = new Proxy(initialStore, {
		set: (target, key, value) => {
		  target[key] = value;
		  // Dispatch a custom event when the store changes
		  document.body.dispatchEvent(new CustomEvent('storeChange', { detail: { key, value } }));
		  this.notify(); // Notify all listeners
		  return true;
		}
	  });
	  this.listeners = [];
	}

	// Get the current store
	getStore() {
	  return this.store;
	}

	// Update the store with new values
	setStore(newStore) {
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

	// Subscribe to store changes
	subscribe(listener) {
	  this.listeners.push(listener);
	  return () => {
		this.listeners = this.listeners.filter(l => l !== listener);
	  };
	}

	// Unsubscribe from store changes
	unsubscribe(listener) {
	  this.listeners = this.listeners.filter(l => l !== listener);
	}

	// Notify all listeners about store changes
	notify(updatedProperties) {
	  this.listeners.forEach(listener => listener(this.store, updatedProperties));
	}
  }

  export default BaseStore;
