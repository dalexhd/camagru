import TemplateEngine from '../lib/templateEngine.js';

export default class {
	comments = [];
	html = null;
	constructor(comments) {
		this.comments = comments;
		this.init();
	}

	init() {
		this.html = new TemplateEngine('#comments-template', this).html;
	}
}
