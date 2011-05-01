/* Browse by module. */
function browseByModule(active)
{
    $('#treebox').removeClass('hidden');
    $('.divider').removeClass('hidden');
    $('#bymoduleTab').addClass('active');
    $('#querybox').addClass('hidden');
    $('#' + active + 'Tab').removeClass('active');
}

$(document).ready(function()
{
    $("a.iframe").colorbox({width:600, height:340, iframe:true, transition:'none'});
    $('#' + browseType + 'Tab').addClass('active'); 
    $('#module' + moduleID).addClass('active'); 

    /* If customed and the browse is ie6, remove the ie6.css. */
    if(customed && $.browser.msie && Math.floor(parseInt($.browser.version)) == 6)
    {
        $("#browsecss").attr('href', '');
    }
});

$(document).ready(function() 
{
    if($('a.export').size()) $("a.export").colorbox({width:400, height:200, iframe:true, transition:'elastic', speed:350, scrolling:true});
})
