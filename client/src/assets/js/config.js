// Routes for the application
import Home from './views/Home';
import Test from './views/Test';

export const title = 'Camagru';

export const routes = [
	{
		path: '/',
		view: Home,
		name: 'Home',
		default: true
	},
	{
		path: '/test',
		name: 'Test',
		view: Test
	}
];
