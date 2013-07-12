$(function() 
{ 
    //$("#submenuchangePassword").colorbox({width:600, height:400, iframe:true, transition:'none', scrolling:false});
    $(function(){$('.iframe').colorbox({width:900, height:500, iframe:true, transition:'none', onCleanup:function(){parent.location.href=parent.location.href;}});})
});

function changeAction(formName, actionName, actionLink)
{
    if(actionName == 'batchClose') $('#' + formName).attr('target', 'hiddenwin');
    $('#' + formName).attr('action', actionLink).submit();
}
