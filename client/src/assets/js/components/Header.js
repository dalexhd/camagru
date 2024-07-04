import { Dom } from "../lib/framework";
const dom = new Dom();

import authStore from "../store/auth";

const isActive = (path) => {
    return window.location.pathname === path ? 'text-white' : 'text-secondary';
};

export default () => {
    const { isLoggedIn, userName } = authStore.getStore();
    const header = dom.createElement('header', { class: 'mb-3 p-3 bg-dark text-white' });
    header.innerHTML = `
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-${isLoggedIn ? 'center' : 'between'}">
                <a href="/" x-href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-decoration-none ${isActive('/')}">
                    Home
                </a>
                ${isLoggedIn ? `
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="/app" x-href="/app" class="nav-link px-2 ${isActive('/app')}">App</a></li>
                    <li><a href="/app/page" x-href="/app/page" class="nav-link px-2 ${isActive('/app/page')}">Page</a></li>
                </ul>` : ''}
                <div class="text-end">
                    ${isLoggedIn ? `
                        <button type="button" class="btn btn-outline-light me-2 logout">Logout</button>
                        <b type="button" class="btn btn-warning">Profile ${userName}</b>
                    ` : `
                        <button type="button" class="btn btn-outline-light me-2 login">Login</button>
                        <button type="button" class="btn btn-warning">Sign-up</button>
                    `}
                </div>
            </div>
        </div>
    `;
    return header;
};
