/* By search. */
function search()
{
    $('#treebox').addClass('hidden');
    $('.divider').addClass('hidden');
    $('#querybox').removeClass('hidden');
    $('#bymoduleTab').removeClass('active');
    $('#bysearchTab').addClass('active');
}
$(function()
{
    $('#' + browseType + 'Tab').addClass('active');
    if(browseType == 'bysearch')search();
});
