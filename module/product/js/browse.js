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

$(document).ready(function() 
{
    if($('a.export').size()) $("a.export").colorbox({width:400, height:200, iframe:true, transition:'elastic', speed:350, scrolling:true});
})
