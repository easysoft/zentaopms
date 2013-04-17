$(function()
{
    setOuterBox();
    if(browseType == 'bysearch') ajaxGetSearchForm();
});

function showProject()
{
    $('#sidebar').hide();
    $('#project').show();
    $('#project-divider').show();
    $.cookie('projectBar', 'show');
}

function hideProject()
{
    $('#sidebar').show();
    $('#project').hide();
    $('#project-divider').hide();
    $.cookie('projectBar', 'hide');
}
