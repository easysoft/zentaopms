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
        $('.btn-install').attr('href', nextLink);
        $('.btn-install').removeClass('disabled');
    }
    else
    {
        $('.btn-install').removeAttr('href');
        $('.btn-install').addClass('disabled');
    }
}

