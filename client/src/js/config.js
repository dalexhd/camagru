// Routes for the application
import Home from './views/Home';
import Test from './views/Test';
import App from './views/App';
import AppPage from './views/AppPage';

// Layouts for the application
import Authenticated from './layouts/Authenticated';
import Unauthenticated from './layouts/Unauthenticated';

export const title = import.meta.env.VITE_APP_TITLE;

export const routes = () => [
	{
		path: '/',
		view: Home,
		name: 'Home',
		layout: Unauthenticated,
		default: true
	},
	{
		path: '/test',
		name: 'Test',
		layout: Authenticated,
		view: Test
	},
	{
		path: '/app',
		layout: Authenticated,
		auth: true,
		name: 'App',
		view: App,
		children: [
			{
				path: '/page',
				name: 'AppPage',
				view: AppPage
			}
		]
	}
];
