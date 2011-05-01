/* Browse by module. */
function browseByModule()
{
    $('#treebox').removeClass('hidden');
    $('.divider').removeClass('hidden');
    $('#bymoduleTab').addClass('active');
    $('#allTab').removeClass('active');
    $('#bysearchTab').removeClass('active');
    $('#querybox').addClass('hidden');
}

$(function(){
    $('#' + browseType + 'Tab').addClass('active');
    if(browseType == "bysearch")search();
});
