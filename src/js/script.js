document.addEventListener('DOMContentLoaded', function () {
    // 1. ✅ Automatické skrytí zprávy (např. po importu)
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

    // 2. ✅ Potvrzení před odhlášením
    const logoutLink = document.querySelector('a[href="logout.php"]');
    if (logoutLink) {
        logoutLink.addEventListener('click', function (e) {
            if (!confirm("Opravdu se chceš odhlásit?")) {
                e.preventDefault();
            }
        });
    }

    // 3. ✅ Klikací řádky tabulky na index.php (data-href)
    document.querySelectorAll('table tr[data-href]').forEach(row => {
        row.addEventListener('click', () => {
            window.location.href = row.dataset.href;
        });
    });
});