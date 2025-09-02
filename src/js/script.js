document.addEventListener('DOMContentLoaded', function () {
    const zprava = document.getElementById('zprava');
    if (zprava) {
        setTimeout(() => {
            zprava.style.transition = 'opacity 0.5s ease-out';
            zprava.style.opacity = '0';
            setTimeout(() => {
                zprava.remove();
            }, 500);
        }, 3000);
    }

    const logoutLink = document.querySelector('a[href="logout.php"]');
    if (logoutLink) {
        logoutLink.addEventListener('click', function (e) {
            if (!confirm("Opravdu se chceš odhlásit?")) {
                e.preventDefault();
            }
        });
    }

    document.querySelectorAll('table tr[data-href]').forEach(row => {
        row.addEventListener('click', () => {
            window.location.href = row.dataset.href;
        });
    });
});