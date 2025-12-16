/**
 * When agree checkbox change.
 *
 * @param event $event
 * @access public
 * @return void
 */
function agreeChange(event)
{
    if($(event.target).prop('checked'))
    {
        $('#confirm').removeClass('disabled').attr('href', $.createLink('upgrade', 'license', 'agree=1'));
    }
    else
    {
        $('#confirm').addClass('disabled').removeAttr('href');
    }
}
