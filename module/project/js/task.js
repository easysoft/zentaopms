$(function()
{
    setOuterBox();
    if(browseType == 'bysearch') ajaxGetSearchForm();
    $('.iframe').colorbox({width:900, height:500, iframe:true, onCleanup:function(){parent.location.href=parent.location.href;}});
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
