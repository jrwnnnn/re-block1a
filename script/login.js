document.getElementById('showPassword').addEventListener('change', function () {
    const pass = document.getElementById('password');
    const confirm = document.getElementById('confirm_password');
    const type = this.checked ? 'text' : 'password';
    pass.type = type;
    confirm.type = type;
});