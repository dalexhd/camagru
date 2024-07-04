import { Dom } from "../lib/framework";
const dom = new Dom();

export default () => {
	const footer = dom.createElement('footer');
	footer.innerHTML = `
		<div class="container">
			<span>Place sticky footer content here.</span>
		</div>
	`;
	return footer;
}
