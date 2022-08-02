$(function()
{
    $(".preview").modalTrigger({width:1000, type:'iframe'});
    $('#' + browseType + 'Tab').addClass('active');

    if(isonlybody == true) parent.$('#triggerModal .modal-content .modal-header .close').hide();
});

/**
 * Go back.
 *
 * @param  int $executionID
 * @access public
 * @return void
 */
function goback(executionID)
{
    var link = createLink('execution', 'ajaxGetExecutionKanban', "executionID=" + executionID);
    $.get(link, function(data)
    {
        if(data)
        {
            kanbanData = $.parseJSON(data);
            parent.updateKanban(kanbanData);
        }
        else
        {
            parent.location.reload();
        }
    });
}
