import './bootstrap';
import * as bootstrap from 'bootstrap';

/**
 * Inisialisasi Bootstrap Components
 */
document.addEventListener('DOMContentLoaded', () => {

    // Tooltip
    const tooltipTriggerList = document.querySelectorAll(
        '[data-bs-toggle="tooltip"]'
    );

    [...tooltipTriggerList].forEach((tooltipTriggerEl) => {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });


    // Popover
    const popoverTriggerList = document.querySelectorAll(
        '[data-bs-toggle="popover"]'
    );

    [...popoverTriggerList].forEach((popoverTriggerEl) => {
        new bootstrap.Popover(popoverTriggerEl);
    });


    // Auto dismiss alert setelah 5 detik
    const alerts = document.querySelectorAll('.alert-auto-close');

    alerts.forEach((alert) => {
        setTimeout(() => {
            const instance = bootstrap.Alert.getOrCreateInstance(alert);
            instance.close();
        }, 5000);
    });


    console.log(
        '%c Bootstrap Loaded Successfully 🚀',
        'color:#7952b3;font-weight:bold;font-size:14px'
    );

});


// Global Bootstrap Access
window.bootstrap = bootstrap;