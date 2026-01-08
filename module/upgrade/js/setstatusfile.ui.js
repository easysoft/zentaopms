function confirmStatusFile(event)
{
    if($(event.target).prop('checked'))
    {
        $('#confirm').removeClass('disabled').attr('onclick', 'loadCurrentPage()');
    }
    else
    {
        $('#confirm').addClass('disabled').removeAttr('onclick');
    }
}
