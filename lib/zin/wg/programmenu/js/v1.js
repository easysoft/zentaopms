function toggleIcon() {
    const $pm = $(e.target).closest('.program-menu');
    const show = $pm.attr('data-show');
    $pm.attr('data-show', (++show) % 2);
}
