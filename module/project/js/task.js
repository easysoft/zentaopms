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
});

/* Browse by project. */
function browseByProject()
{
    $('#querybox').addClass('hidden');
    $('#byProjectTab').addClass('active');
    $('#featurebar .active').removeClass('active');
}

