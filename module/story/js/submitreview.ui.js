window.toggleReviewer = function(obj)
{
    const $this     = $(obj);
    const isChecked = $this.prop('checked');
    $('#reviewer').val(isChecked ? '' : lastReviewer).attr('disabled', isChecked ? 'disabled' : null).trigger('chosen:updated');
    $('#reviewerBox .form-label').toggleClass('required', !isChecked);
}
if(!$('#reviewer').val()) toggleReviewer($('#needNotReview'));
