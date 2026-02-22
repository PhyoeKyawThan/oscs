import './bootstrap';
import CartManager from "./cartManager";
import Alpine from 'alpinejs';

window.Alpine = Alpine;
document.addEventListener('DOMContentLoaded', () => {
    Alpine.start();
});
window.cartManager = new CartManager();
Alpine.start();