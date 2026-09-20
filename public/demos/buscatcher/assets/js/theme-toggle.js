/**
 * Theme Toggle Functionality
 * Handles switching between light and dark modes with smooth transitions
 * Matches Dark Reader aesthetic for optimal viewing experience
 */

document.addEventListener('DOMContentLoaded', function() {
    // Add smooth transitions AFTER page load to avoid flash
    const style = document.createElement('style');
    style.textContent = `
        body {
            transition: background-color 0.25s ease, color 0.25s ease;
        }
        .card, .navbar, .sidebar, .menu-inner, input, textarea, select, table {
            transition: background-color 0.25s ease, color 0.25s ease, border-color 0.25s ease;
        }
    `;
    document.head.appendChild(style);

    initializeThemeToggle();
});

function initializeThemeToggle() {
    const themeToggleBtn = document.getElementById('theme-toggle-btn');
    const themeToggleIcon = document.getElementById('theme-toggle-icon');
    const htmlElement = document.documentElement;

    if (!themeToggleBtn || !themeToggleIcon) return;

    // Get saved theme preference or detect system preference
    const savedTheme = localStorage.getItem('theme-mode');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const currentTheme = savedTheme || (systemPrefersDark ? 'dark' : 'light');

    // Apply the theme on page load
    applyTheme(currentTheme);

    // Add click handler
    themeToggleBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const newTheme = htmlElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
        applyTheme(newTheme);
    });

    // Listen for system theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (!localStorage.getItem('theme-mode')) {
            applyTheme(e.matches ? 'dark' : 'light');
        }
    });
}

function applyTheme(theme) {
    const htmlElement = document.documentElement;
    const themeToggleIcon = document.getElementById('theme-toggle-icon');

    if (!themeToggleIcon) return;

    // Update HTML data attribute
    htmlElement.setAttribute('data-bs-theme', theme);

    // Update localStorage
    localStorage.setItem('theme-mode', theme);

    // Update icon
    if (theme === 'dark') {
        themeToggleIcon.classList.remove('ri-sun-line');
        themeToggleIcon.classList.add('ri-moon-line');
        themeToggleIcon.parentElement.title = 'Switch to Light Mode';
    } else {
        themeToggleIcon.classList.remove('ri-moon-line');
        themeToggleIcon.classList.add('ri-sun-line');
        themeToggleIcon.parentElement.title = 'Switch to Dark Mode';
    }

    // Trigger custom event for other components
    window.dispatchEvent(new CustomEvent('themeChange', { detail: { theme: theme } }));
}

// Export function for manual theme switching
window.toggleTheme = function() {
    const htmlElement = document.documentElement;
    const newTheme = htmlElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
    applyTheme(newTheme);
};
