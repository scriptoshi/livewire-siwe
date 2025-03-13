// Import your CSS
import '../css/app.css';

// Import other dependencies
import './bootstrap';

// Import SIWE
import { createSiwe } from '../vendor/scriptoshi/livewire-siwe/js/siwe.js';

// Initialize Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Initialize SIWE when the document is loaded
document.addEventListener('DOMContentLoaded', function() {
    createSiwe();
});
