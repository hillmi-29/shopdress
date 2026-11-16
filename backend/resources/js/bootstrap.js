import axios from 'axios';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Token CSRF dari <meta name="csrf-token"> pada layout, dibutuhkan
// endpoint POST /api/* karena ValidateCsrfTokens berlaku global.
const csrfToken = document.head.querySelector('meta[name="csrf-token"]');

if (csrfToken) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.content;
}
