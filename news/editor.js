/**
 * Tracks unsaved changes in the post form and prompts the user before leaving the page to prevent data loss.
 */

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
        return confirmationMessage;
    }
});

document.querySelector('#postForm').addEventListener('submit', function() {
    isDirty = false;
});

