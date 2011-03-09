/* Search bugs. */
function browseBySearch(active)
{
    $('.divider').addClass('hidden');
    $('#querybox').removeClass('hidden');
    $('#querybox').removeClass('hidden');
    $('#bymoduleTab').removeClass('active');
    $('#' + active + 'Tab').removeClass('active');
    $('#bysearchTab').addClass('active');
}
