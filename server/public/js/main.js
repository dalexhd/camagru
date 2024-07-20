// Modules
import NotificationModule from './modules/notification.js';
import CommentModule from './modules/comment.js';


document.addEventListener('DOMContentLoaded', () => {
	// Modules initialization
	const notificationModule = new NotificationModule();
	notificationModule.init();

	const commentModule = new CommentModule();
	commentModule.init();
});
