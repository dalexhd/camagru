import { Dom } from "../lib/framework";
const dom = new Dom();

export default function () {
	const main = dom.createElement('main', { class: 'main flex-shrink-0' });
	main.innerHTML = `
		<div class="container">
			<p>Content goes here</p>
		</div>
	`;
	return main;
}
