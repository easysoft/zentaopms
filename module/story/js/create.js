$(function()
{
    $('#needNotReview').on('change', function() {
        $('#assignedTo').attr('disabled', $(this).is(':checked') ? 'disabled' : null).trigger('chosen:updated');
    });
});
