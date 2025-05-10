function loadingShort() {
    document.getElementById('loading').classList.remove('invisible');
    document.getElementById('loading').classList.add('visible');
    const delay = Math.floor(Math.random() * 5 + 1) * 1000;
    setTimeout(() => {
        document.getElementById('loading').classList.add('invisible');
    }, delay);
}

function loadingLong() {
    document.getElementById('loading').classList.remove('invisible');
    document.getElementById('loading').classList.add('visible');
    const delay = Math.floor(Math.random() * 6 + 5) * 1000;
    setTimeout(() => {
        document.getElementById('loading').classList.remove('visible');
        document.getElementById('loading').classList.add('visible');
    }, delay);
}

