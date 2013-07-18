$(function() 
{ 
    if(typeof(listName) != 'undefined') setModal4List('iframe', listName, function(){$(".colorbox").colorbox({width:960, height:550, iframe:true, transition:'none'});});
});

function changeAction(formName, actionName, actionLink)
{
    if(formName =='myTaskForm' && actionName == 'batchClose') $('#' + formName).attr('target', 'hiddenwin');
    $('#' + formName).attr('action', actionLink).submit();
}
