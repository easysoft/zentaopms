window.changeOption = function(event)
{
    $('.think-options-field').toggleClass('hidden', event.target.value == 1);
    $('.think-quote-title').toggleClass('hidden', event.target.value == 0);
    $('.min-count input').val(1).attr('disabled', 'disabled');
    $('.max-count input').attr('placeholder', maxCountPlaceholder).attr('disabled', 'disabled');
}
