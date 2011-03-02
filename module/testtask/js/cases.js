function checkall(checker)
{
    $('input').each(function() 
    {
        $(this).attr("checked", checker.checked)
    });
}

function browseByModule(active)
{
    $('.side').removeClass('hidden');
    $('.divider').removeClass('hidden');
    $('#bymoduleTab').addClass('active');
    $('#' + active + 'Tab').removeClass('active');
}

$(document).ready(function()
{
    $("a.iframe").colorbox({width:900, height:600, iframe:true, transition:'none'});
    $('#' + browseType + 'Tab').addClass('active');
    $('#module' + moduleID).addClass('active'); 
});
