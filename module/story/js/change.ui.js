$(function()
{
    $(document).on('change', '#needNotReview', function()
    {
        toggleReviewer();
    });
    toggleReviewer();
});

function toggleReviewer()
{
    var $this      = $('#needNotReview');
    var $formGroup = $this.closest('.form-group');
    var isChecked  = $this.prop('checked');
    $('#reviewer').val(isChecked ? '' : lastReviewer).attr('disabled', isChecked ? 'disabled' : null);
    $formGroup.find('.form-label').toggleClass('required', !isChecked);
}
