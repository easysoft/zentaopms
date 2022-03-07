$(function()
{
    $(".preview").modalTrigger({width:1000, type:'iframe'});
    $('#' + browseType + 'Tab').addClass('active');

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
