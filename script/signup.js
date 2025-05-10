const form = document.querySelector('form');
    form.addEventListener('submit', (e) => {
        if (strength < 3) {
            e.preventDefault();
            alert("Password is too weak. Use at least 8 characters with uppercase, lowercase, numbers, and symbols.");
        }
    });

document.getElementById('showPassword').addEventListener('change', function () {
    const pass = document.getElementById('password');
    const confirm = document.getElementById('confirm_password');
    const type = this.checked ? 'text' : 'password';
    pass.type = type;
    confirm.type = type;
});

