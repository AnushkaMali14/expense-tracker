// Client-side form validation
document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.getElementById('registerForm');
    const loginForm = document.getElementById('loginForm');

    if (registerForm) {
        registerForm.addEventListener('submit', (e) => {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;

            if (password.length < 6) {
                alert('Password must be at least 6 characters long.');
                e.preventDefault();
            } else if (password !== confirm) {
                alert('Passwords do not match.');
                e.preventDefault();
            }
        });
    }

    // Theme Toggle Logic
    const initTheme = () => {
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
    };

    initTheme();
});

function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
}
