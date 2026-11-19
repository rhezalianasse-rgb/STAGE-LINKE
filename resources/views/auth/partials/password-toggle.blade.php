<script>
    document.querySelectorAll('[data-password-toggle]').forEach((toggle) => {
        const password = document.getElementById(toggle.dataset.passwordToggle);

        if (!password) return;

        toggle.addEventListener('click', () => {
            const isVisible = password.type === 'text';
            password.type = isVisible ? 'password' : 'text';
            toggle.setAttribute('aria-pressed', String(!isVisible));
            toggle.setAttribute('aria-label', isVisible ? 'Afficher le mot de passe' : 'Masquer le mot de passe');
            toggle.innerHTML = `<i class="fa-regular ${isVisible ? 'fa-eye' : 'fa-eye-slash'}" aria-hidden="true"></i>`;
        });
    });
</script>
