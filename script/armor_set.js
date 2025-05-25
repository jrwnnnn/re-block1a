document.querySelectorAll('.armor-piece').forEach(piece => {
    const tooltip = piece.querySelector('.armor-tooltip');
    piece.addEventListener('mouseenter', e => {
        tooltip.style.display = 'flex';
    });
    piece.addEventListener('mouseleave', e => {
        tooltip.style.display = 'none';
    });
    piece.addEventListener('mousemove', e => {
        const offsetX = 20;
        const offsetY = 20;
        tooltip.style.left = (e.offsetX + offsetX) + 'px';
        tooltip.style.top = (e.offsetY + offsetY) + 'px';
    });
});
