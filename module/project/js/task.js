$(function()
{
    if($.cookie('projectBar') == 'hide')
    {
        $('#project').hide();
        $('#project-divider').hide();
        setOuterBox();
    }
    else
    {
        $('#sidebar').hide();
        setOuterBox();
    }
    if(browseType == 'bysearch') ajaxGetSearchForm();
    $('.iframe').colorbox({width:900, height:500, iframe:true});
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
