/* Search bugs. */
function browseBySearch(active)
{
    $('.divider').addClass('hidden');
    $('#querybox').removeClass('hidden');
    $('#' + active + 'Tab').removeClass('active');
}
