$(function()
{
    setOuterBox();
    if(browseType == 'bysearch') ajaxGetSearchForm();
});

function changeAction(formName, actionName, actionLink)
{
    if(actionName == 'batchClose') $('#' + formName).attr('target', 'hiddenwin');
    $('#' + formName).attr('action', actionLink).submit();
}

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
