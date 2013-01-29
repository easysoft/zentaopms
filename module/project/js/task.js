$(function()
{
    if($.cookie('projectBar') == 'hide')
    {
        $('#project').hide();
        setOuterBox();
    }
    else
    {
        $('#sidebar').hide();
        setOuterBox();
    }
    if(browseType == 'bysearch') ajaxGetSearchForm();
    $('.iframe').colorbox({width:900, height:400, iframe:true});
});

function showProject()
{
    $('#sidebar').hide();
    $('#project').show();
    $.cookie('projectBar', 'show');
}

function hideProject()
{
    $('#sidebar').show();
    $('#project').hide();
    $.cookie('projectBar', 'hide');
}
