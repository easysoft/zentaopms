/* Browse by module. */
function browseByModule()
{
    $('#querybox').addClass('hidden');
    $('#featurebar .active').removeClass('active');
    $('#bymoduleTab').addClass('active');
}

$(function()
{
    $('#' + browseType + 'Tab').addClass('active');
    if(browseType == 'bysearch') ajaxGetSearchForm();
    $('.iframe').colorbox({width:900, height:400, iframe:true});
});

/* Browse by project. */
function browseByProject()
{
    $('#querybox').addClass('hidden');
    $('#byProjectTab').addClass('active');
    $('#featurebar .active').removeClass('active');
}

