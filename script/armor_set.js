function isMdScreen() {
    return window.matchMedia('(max-width: 768px)').matches;
}

document.querySelectorAll('.armor-piece').forEach(piece => {
    const tooltip = piece.querySelector('.armor-tooltip');
    piece.addEventListener('mouseenter', e => {
        if (isMdScreen()) return;
        tooltip.style.display = 'flex';
    });
    piece.addEventListener('mouseleave', e => {
        if (isMdScreen()) return;
        tooltip.style.display = 'none';
    });
    piece.addEventListener('mousemove', e => {
        if (isMdScreen()) return;
        const offsetX = 20;
        const offsetY = 20;
        tooltip.style.left = (e.offsetX + offsetX) + 'px';
        tooltip.style.top = (e.offsetY + offsetY) + 'px';
    });
    // Hide tooltip if screen is md on load/resize
    function hideOnMd() {
        if (isMdScreen()) tooltip.style.display = 'none';
    }
    window.addEventListener('resize', hideOnMd);
    hideOnMd();
});
