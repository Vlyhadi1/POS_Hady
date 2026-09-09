import './bootstrap';
import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
    document.querySelectorAll('[data-bs-toggle="popover"]').forEach(el => new bootstrap.Popover(el));

    document.querySelectorAll('.global-alert').forEach(alert => {
        setTimeout(() => {
            if (alert && document.body.contains(alert)) alert.remove();
        }, 5000);
    });
});
