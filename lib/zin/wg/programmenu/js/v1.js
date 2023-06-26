function toggleIcon(e) {
    const $pm = $(e.target).closest('.program-menu');
    let show = $pm.attr('data-show');
    $pm.attr('data-show', (++show) % 2);
}
