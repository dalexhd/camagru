import { Dom } from "../lib/framework";
const dom = new Dom();

export default function () {
	const footer = dom.createElement('footer', { class: 'footer mt-auto py-3 bg-light' });
	footer.innerHTML = `
		<div class="container">
			<span class="text-muted">Place sticky footer content here.</span>
		</div>
	`;
	return footer;
}
