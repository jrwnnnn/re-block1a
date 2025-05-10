let isDirty = false; 

function setDirty() {
    isDirty = true;
}

document.querySelectorAll('#postForm input, #postForm textarea').forEach(function(input) {
    input.addEventListener('input', setDirty);
});

window.addEventListener('beforeunload', function(event) {
    if (isDirty) {
        const confirmationMessage = 'You have unsaved changes. Are you sure you want to leave?';
        
        (event || window.event).returnValue = confirmationMessage; 
        return confirmationMessage; // For other browsers (standard)
    }
});

document.querySelector('#postForm').addEventListener('submit', function() {
    isDirty = false;
});

function formLoading() {
    document.getElementById('loading').classList.remove('invisible');
    document.getElementById('loading').classList.add('visible');
    const delay = Math.floor(Math.random() * 6 + 5) * 1000;
    setTimeout(() => {
        document.getElementById('loading').classList.remove('visible');
        document.getElementById('loading').classList.add('invisible');
        const form = document.querySelector('form');
        if (form) {
            form.action = "../functions/submit-article.php";
            form.method = "POST";
            form.submit();
        }
    }, delay);
}
