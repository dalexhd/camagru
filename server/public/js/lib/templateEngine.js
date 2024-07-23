export default class TemplateEngine {
	constructor(selector, data) {
		this.template = document.querySelector(selector)?.innerHTML;
		if (!this.template) {
			console.error(`Template not found: ${selector}`);
			return;
		}
		this.data = data;
		this.partials = {};
		this.loadPartials();
		this.html = this.compile(this.template, data);
	}

	loadPartials() {
		document.querySelectorAll('script[type="text/x-camgru-template"]').forEach(partial => {
			this.partials[partial.id.replace('-template', '')] = partial.innerHTML;
		});
	}

	compile(_template = null, _context = null) {
		let template = _template ?? this.template;
		let context = _context ?? this.data;

		// Handle each loops
		template = template.replace(/{{#each\s+([\w.]+)\s*}}([\s\S]*?){{\/each}}/g, (match, key, content) => {
			const array = key.split('.').reduce((obj, k) => obj && obj[k], context);
			return array.map(item => this.compile(content, item)).join('');
		});

		// Handle conditionals
		template = template.replace(/{{#if\s*([\w.]+)\s*}}([\s\S]*?){{\/if}}/g, (match, key, content) => {
			const condition = key.split('.').reduce((obj, k) => obj && obj[k], context);
			return condition ? this.compile(content, context) : '';
		});

		// Handle partials
		template = template.replace(/{{>\s*([\w-]+)\s*}}/g, (match, partialName) => {
			const partial = this.partials[partialName];
			if (!partial) {
				console.error(`Partial not found: ${partialName}`);
				return match;
			}
			return this.compile(partial, context);
		});

		// Replace placeholders
		return template.replace(/{{\s*([\w.]+)\s*}}/g, (match, key) => {
			const value = key.split('.').reduce((obj, k) => obj && obj[k], context);
			return value ?? '';
		});
	}
}
