/**
 * Small Fish Business - Main JavaScript
 */

// Import CSS for Vite processing
import '../css/main.css';

// Import modules
import { initMobileMenu } from './modules/mobile-menu.js';

// DOM Ready
document.addEventListener('DOMContentLoaded', () => {
    // Initialize mobile menu
    initMobileMenu();
});
