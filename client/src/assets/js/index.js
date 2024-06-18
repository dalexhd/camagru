import { Dom, Observer } from "./lib/framework";

document.addEventListener('DOMContentLoaded', () => {

	const dom = new Dom();
	const observer = new Observer();

	const app = document.getElementById('app');
	fetch('/api/data')
		.then((response) => response.json())
		.then((data) => {
			const message = dom.createElement('p', {}, `Data received from server: ${data.message}`);
			dom.append(app, message);


			const button = dom.createElement('button', {}, 'Click me');
			dom.append(app, button);

			dom.on(button, 'click', () => {
				observer.emit('button-clicked', 'Button clicked');
			});

			observer.on('button-clicked', (data) => {
				const message = dom.createElement('p', {}, data);
				dom.append(app, message);
			});
		});

});
