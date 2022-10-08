function reload(toProject, fromProject)
{
    link = createLink('execution','importtask','toProject='+toProject + '&fromProject='+fromProject);
    location.href = link;
}

$(function()
{
    $(".preview").modalTrigger({width:1000, type:'iframe'});

    if(isonlybody == true) parent.$('#triggerModal .modal-content .modal-header .close').hide();
});

/**
 * Go back.
 *
 * @access public
 * @return void
 */
function goback()
{
    parent.location.reload();
}
