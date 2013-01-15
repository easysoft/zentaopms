function changeDate(date)
{
    date = date.replace(/\-/g, '');
    link = createLink('my', 'todo', 'type=' + date);
    location.href=link;
}

/**
 * Change form action.
 * 
 * @param  formName   $formName 
 * @param  actionName $actionName 
 * @param  actionLink $actionLink 
 * @access public
 * @return void
 */
function changeAction(formName, actionName, actionLink)
{
    $('#' + formName).attr('action', actionLink).submit();
}

$(".colorbox").colorbox({width:960, height:600, iframe:true, speed:350, scrolling:true});
