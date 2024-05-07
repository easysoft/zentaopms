window.handleInput = function()
{
    if($(this).closest('.radio-primary').length)
    {
        $(this).closest('.think-check-list').find('.radio-primary').removeClass('checked');
        $(this).closest('.radio-primary').toggleClass('checked');
    }
    else
    {
        $(this).closest('.checkbox-primary').toggleClass('checked', $(this).is(':checked'));
    }

    if($(this).closest('.item-input').length)
    {
        $(this).closest('.item-input').find('input[type="text"]').prop('disabled', !$(this).is(':checked'));
    }
    else
    {
        $(this).closest('.think-check-list').find('.item-input input[type="text"]').prop('disabled', true);
    }
}
