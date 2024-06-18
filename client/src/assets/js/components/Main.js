import { Dom } from "../lib/framework";
const dom = new Dom();

const main = dom.createElement('main', { class: 'main flex-shrink-0' });
main.innerHTML = `
	<div class="container">
		<p>Content goes here</p>
	</div>
`;

export default main;
