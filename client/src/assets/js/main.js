import { Dom, Observer } from "./lib/framework";

document.addEventListener('DOMContentLoaded', () => {
	const app = document.getElementById('app');
	const button = Dom.createElement('button', {}, 'Click me');
	Dom.append(app, button);

	Dom.on(button, 'click', () => {
		Observer.emit('button-clicked', 'Button clicked');
	});

	Observer.on('button-clicked', (data) => {
		const message = Dom.createElement('p', {}, data);
		Dom.append(app, message);
	});

	setTimeout(() => {
		Dom.off(button, 'click');
		Observer.off('button-clicked');
	}, 5000);
});
