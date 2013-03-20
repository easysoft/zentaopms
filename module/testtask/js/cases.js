function browseByModule(active)
{
    $('.side').removeClass('hidden');
    $('.divider').removeClass('hidden');
    $('#bymoduleTab').addClass('active');
    $('#' + active + 'Tab').removeClass('active');
}

$(document).ready(function()
{
    $("a.iframe").colorbox({width:900, height:550, iframe:true, transition:'none', onCleanup:function(){parent.location.href=parent.location.href;}});
    $('#' + browseType + 'Tab').addClass('active');
    $('#module' + moduleID).addClass('active'); 
});
