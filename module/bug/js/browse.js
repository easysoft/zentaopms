/* Browse by module. */
function browseByModule(active)
{
    $('#treebox').removeClass('hidden');
    $('#bymoduleTab').addClass('active');
    $('#querybox').addClass('hidden');
    $('#' + active + 'Tab').removeClass('active');
}
/* Search bugs. */
function browseBySearch(active)
{
    $('#treebox').addClass('hidden');
    $('#querybox').removeClass('hidden');
    $('#bymoduleTab').removeClass('active');
    $('#' + active + 'Tab').removeClass('active');
    $('#bysearchTab').addClass('active');
}

$(document).ready(function()
{
    $("a.iframe").colorbox({width:640, height:420, iframe:true, transition:'none'});
    $('#' + browseType + 'Tab').addClass('active'); 
    $('#module' + moduleID).addClass('active'); 
});
