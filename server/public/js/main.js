// Modules
import NotificationModule from './modules/notification.js';
import AuthModule from './modules/auth.js';
import CommentModule from './modules/comment.js';
import PostsModule from './modules/posts.js';


document.addEventListener('DOMContentLoaded', () => {
	// Modules initialization
	const notificationModule = new NotificationModule();
	notificationModule.init();

	if (document.querySelector('#post-wrapper')) {
		const commentModule = new CommentModule();
		const postsModule = new PostsModule();

		Promise.all([
			commentModule.init(),
			postsModule.init()
		]).then(() => {
			// Initialize the rest of the modules
			initModules();
		});
	}

	const initModules = () => {
		const authModule = new AuthModule(isLoggedIn);
		authModule.init();
	}
});
