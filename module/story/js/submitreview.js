$(function()
{
    $('#needNotReview').on('change', function()
    {
        $('#reviewer').val($(this).is(':checked') ? '' : lastReviewer).attr('disabled', $(this).is(':checked') ? 'disabled' : null).trigger('chosen:updated');
        if($(this).is(':checked'))
        {
            $('#reviewerBox').removeClass('required');
        }
        else
        {
            $('#reviewerBox').addClass('required');
        }
    });
    if(!$('#reviewer').val()) $('#needNotReview').change();
});
