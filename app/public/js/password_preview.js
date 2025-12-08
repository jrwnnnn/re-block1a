const showPasswordCheckbox = document.getElementById('showPassword');
if (showPasswordCheckbox) {
    showPasswordCheckbox.addEventListener('change', function () {
        const current_password = document.getElementById('current_password');
        const password = document.getElementById('password');
        const confirm_password = document.getElementById('confirm_password');
        const new_password = document.getElementById('new_password');
        const confirm_new_password = document.getElementById('confirm_new_password');
        const type = this.checked ? 'text' : 'password';
        
        if (current_password) current_password.type = type;
        if (password) password.type = type;
        if (confirm_password) confirm_password.type = type;
        if (new_password) new_password.type = type;
        if (confirm_new_password) confirm_new_password.type = type;
    });
}