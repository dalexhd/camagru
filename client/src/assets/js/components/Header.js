import { Dom } from "../lib/framework";
const dom = new Dom();

const header = dom.createElement('header', { class: 'mb-3 p-3 bg-dark text-white' });
header.innerHTML = `
	<div class="container">
		<div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
			<a href="/" x-href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
				Logo
			</a>
			<ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
				<li><a href="/app" x-href="/app" class="nav-link px-2 text-secondary">App</a></li>
				<li><a href="/app/page" x-href="/app/page" class="nav-link px-2 text-secondary">Home</a></li>

				<li><a href="/app/page" x-href="/app/page" class="nav-link px-2 text-secondary"></a></li>
			</ul>

			<div class="text-end">
				<div x-if="state.isLoggedIn">
					<button type="button" class="btn btn-outline-light me-2">Logout</button>
					<button type="button" class="btn btn-warning">Profile</button>
				</div>
				<div x-else>
					<button type="button" class="btn btn-outline-light me-2">Login</button>
					<button type="button" class="btn btn-warning">Sign-up</button>
				</div>
			</div>
		</div>
	</div>
`;

export default header;
