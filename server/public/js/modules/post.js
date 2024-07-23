import TemplateEngine from '../lib/templateEngine.js';
export default class {
	constructor(post) {
		this.post = post;
		this.init();
	}

	init() {
		this.html = {
			post: new TemplateEngine('#post-template', this.post).html,
			comments: new TemplateEngine('#comments-template', this.post).html
		};
	}
}
