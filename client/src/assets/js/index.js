import { Dom, Observer } from "./lib/framework";
import Router from "./lib/framework/router";

// Instances
const dom = new Dom();
const observer = new Observer();

// Components
import Footer from "./components/Footer";
import Header from "./components/Header";
import Main from "./components/Main";

// Config
import { routes, title } from "./config";

const setupLayout = () => {
	const app = document.getElementById('app');
	app.appendChild(Header);
	app.appendChild(Main);
	app.appendChild(Footer);
}

const setupRoutes = () => {
	const router = new Router(routes, title);
}

document.addEventListener('DOMContentLoaded', () => {
	setupLayout();
	setupRoutes();

	// fetch('/api/data')
	// 	.then((response) => response.json())
	// 	.then((data) => {
	// 		let main = document.querySelector('main > div.container');

	// 		const message = dom.createElement('p', {}, `Data received from server: ${data.message}`);
	// 		dom.append(main, message);

	// 		const button = dom.createElement('button', {}, 'Click me');
	// 		dom.append(main, button);

	// 		dom.on(button, 'click', () => {
	// 			observer.emit('button-clicked', 'Button clicked');
	// 		});

	// 		observer.on('button-clicked', (data) => {
	// 			const message = dom.createElement('p', {}, data);
	// 			dom.append(main, message);
	// 		});
	// 	});
});
