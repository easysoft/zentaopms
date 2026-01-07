function confirmStatusFile(event)
{
    if($(event.target).prop('checked'))
    {
        $('#confirm').removeClass('disabled').attr('href', $.createLink('upgrade', 'index'));
    }
    else
    {
        $('#confirm').addClass('disabled').removeAttr('href');
    }
}
