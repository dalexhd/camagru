class ReactiveHandler {
  constructor(data) {
    this.data = data;
    this.listeners = [];
  }

  get(target, prop) {
    if (typeof target[prop] === "object" && target[prop] !== null) {
      return new Proxy(target[prop], this);
    }
    return target[prop];
  }

  set(target, prop, value) {
    if (target[prop] !== value) {
      target[prop] = value;
      this.notify(prop);
    }
    return true;
  }

  notify(prop) {
    this.listeners.forEach((listener) => listener(prop));
  }

  subscribe(listener) {
    this.listeners.push(listener);
  }
}

export const reactive = (data) => {
  const handler = new ReactiveHandler(data);
  const proxy = new Proxy(data, handler);
  handler.proxy = proxy;
  return handler;
};
