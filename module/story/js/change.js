$(function()
{
    $('[data-toggle="tooltip"]').tooltip();
    $('#needNotReview').on('change', function()
    {
        $('#reviewer').val($(this).is(':checked') ? '' : lastReviewer).attr('disabled', $(this).is(':checked') ? 'disabled' : null).trigger('chosen:updated');
        if($(this).is(':checked'))
        {
            $('.input-group-addon').removeClass('required');
        }
        else
        {
            $('.input-group-addon').addClass('required');
        }
    });
    if(!$('#reviewer').val()) $('#needNotReview').change();

    if($('.tabs .tab-content .tab-pane.active').children().length == 0) $('.tabs .nav-tabs li.active').css('border-bottom', '1px solid #ccc');
});
