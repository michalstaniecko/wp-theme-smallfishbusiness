/**
 * Small Fish Business - Main JavaScript
 */

// Import CSS for Vite processing
import '../css/main.css';

// Import modules
import { initMobileMenu } from './modules/mobile-menu.js';
import { initDropdownNav } from './modules/dropdown-nav.js';
import { initTableOfContents } from './modules/table-of-contents.js';

// DOM Ready
document.addEventListener('DOMContentLoaded', () => {
    // Initialize mobile menu
    initMobileMenu();

    // Initialize desktop dropdown navigation
    initDropdownNav();

    // Initialize Table of Contents
    initTableOfContents();
});
