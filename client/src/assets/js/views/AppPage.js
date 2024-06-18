import view from "../lib/framework/dom/view"

export default class extends view {
	constructor() {
		super();
		this.name = 'AppPage';
	}

	async render() {
		const main = document.querySelector('main');
		main.innerHTML = `
			<div class="container">
				<h1>${this.name}</h1>
			</div>
		`;
	}

	onMount() {
		console.info('[View] AppPage view mounted');
	}

	onDestroy() {
		console.info('[View] AppPage view destroyed');
	}
}
