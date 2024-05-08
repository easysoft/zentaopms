window.stopPropagation = function(event)
{
    event.stopPropagation();
}

window.toggleChecked = function()
{
    if($(this).data('type') == 'radio')
    {
        $(this).find('input').prop('checked', !$(this).find('input').prop('checked'));
        $(this).closest('.think-check-list').find('.item-control').removeClass('is-checked');
        $(this).toggleClass('is-checked', $(this).find('input').is(':checked'));
        $(this).closest('.think-check-list').find('.radio-primary').removeClass('checked');
        $(this).find('.radio-primary').toggleClass('checked');
    }
    else
    {
        $(this).find('input').prop('checked', !$(this).find('input').prop('checked'));
        $(this).find('.checkbox-primary').toggleClass('checked', $(this).find('input').is(':checked'));
        $(this).toggleClass('is-checked', $(this).find('input').is(':checked'));
    }

    if($(this).hasClass('has-input'))
    {
        $(this).find('textarea').prop('disabled', !$(this).find('input').prop('checked'));
        if($(this).find('input').prop('checked')) $(this).find('textarea').trigger('focus');
    }
    else
    {
        if($(this).data('type') == 'radio') $(this).closest('.think-check-list').find('.has-input textarea').prop('disabled', true);
    }
}

window.inputOther = function()
{
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight > 96 ? 96 : this.scrollHeight) + 'px';
}
