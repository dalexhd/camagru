// Modules
import NotificationModule from './modules/notification.js';
import CommentModule from './modules/comment.js';
import PostsModule from './modules/posts.js';


document.addEventListener('DOMContentLoaded', () => {
	// Modules initialization
	const notificationModule = new NotificationModule();
	notificationModule.init();

	if (document.querySelector('#post-wrapper')) {
		const commentModule = new CommentModule();
		commentModule.init();
	
		const postsModule = new PostsModule();
		postsModule.init();
	}
});
