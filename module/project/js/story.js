$(document).ready(function()
{
    $("a.iframe").colorbox({width:640, height:480, iframe:true, transition:'none'});
    $("a.batchWBS").colorbox({width:1024, height:580, iframe:true, transition:'none'});
});

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
