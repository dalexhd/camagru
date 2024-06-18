import Layout from "../lib/framework/dom/layout"

// Components
import Header from "../components/Header";
import Main from "../components/Main";


export default class extends Layout {
	constructor() {
		super();
		this.name = 'Authenticated';
	}

	async render() {
		const app = document.getElementById('app');
		app.appendChild(Header);
		app.appendChild(Main);
		const main = app.querySelector('main');
		main.innerHTML = `
			<router-view></router-view>
		`;
	}
}
