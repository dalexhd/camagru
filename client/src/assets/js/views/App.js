import view from "../lib/framework/dom/view"

export default class extends view {
	constructor() {
		super();
		this.name = 'App';
	}

	async render() {
		const main = document.querySelector('main');
		main.innerHTML = `
			<div class="container">
				<h1>${this.name} {{ state.userName }}</h1>
			</div>
		`;
	}

	onMount() {
		console.info('[View] App view mounted');
	}

	onDestroy() {
		console.info('[View] App view destroyed');
	}
}
