function toggleDateVisibility()
{
    if($('#future_').prop('checked'))
    {
        $('#begin').attr('disabled', 'disabled');
        $('#end').attr('disabled', 'disabled').closest('.form-row').hide();
    }
    else
    {
        $('#begin').removeAttr('disabled');
        $('#end').removeAttr('disabled').closest('.form-row').show();
    }
}
