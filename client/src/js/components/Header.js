import { Dom } from "../lib/framework";
const dom = new Dom();

import authStore from "../store/auth";

const isActive = (path) => {
    return window.location.pathname === path ? 'active' : '';
};

export default () => {
    const { isLoggedIn, userName } = authStore.getStore();
    const header = dom.createElement('header');
    header.innerHTML = `
        <div class="container">
            <a href="/" x-href="/" class="${isActive('/')}">
                Home
            </a>
            ${isLoggedIn ? `
                <a href="/test" x-href="/test" class="${isActive('/test')}">Test</a>
                <a href="/app" x-href="/app" class="${isActive('/app')}">App</a>
                <a href="/app/page" x-href="/app/page" class="${isActive('/app/page')}">Page</a>
                <button type="button" class="logout">Logout</button>
                <b type="button">Profile ${userName}</b>`
            : `
                <button type="button" class="login">Login</button>
                <button type="button">Sign-up</button>
            `}
        </div>
    `;
    return header;
};
