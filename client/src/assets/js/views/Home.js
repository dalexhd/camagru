import view from "../lib/framework/dom/view"

export default class extends view {
	constructor() {
		super();
		this.name = 'Home';
	}

	async render() {
		const main = document.querySelector('main');
		main.innerHTML = `
			<div class="container">
				<h1>${this.name}</h1>
				<p>Home content goes here</p>
			</div>
		`;
	}

	onMount() {
		console.info('[View] Home view mounted');
	}

	onDestroy() {
		console.info('[View] Home view destroyed');
	}
}
