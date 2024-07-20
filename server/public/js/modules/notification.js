import CamagruModule from './module.js';

export default class extends CamagruModule {
	constructor() {
		super();
		console.log('Notification constructor');
	}

	init() {
		// Close notification
		(document.querySelectorAll('.notification .delete') || []).forEach(($delete) => {
			const $notification = $delete.parentNode;

			$delete.addEventListener('click', () => {
				$notification.parentNode.removeChild($notification);
			});
		});

		// Auto-hide notification
		(document.querySelectorAll('.notification') || []).forEach(($notification) => {
			setTimeout(() => {
				$notification.parentNode.removeChild($notification);
			}, 3000);
		});
	}
}
