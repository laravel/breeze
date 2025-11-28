// Immediately set theme on page load
(function () {
    try {
        const stored = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        const isDark = stored === 'dark' || (!stored && prefersDark);

        document.documentElement.classList.toggle('dark', isDark);
        document.documentElement.style.colorScheme = isDark ? 'dark' : 'light';
    } catch (e) {
        console.warn('Theme initialization failed:', e);
    }
})();

// Export function for module import
export function toggleTheme() {
    const html = document.documentElement;
    const isDark = html.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    html.style.colorScheme = isDark ? 'dark' : 'light';
}
