window.clickAddRows = function()
{
    const rowIndex =  $(this).closest('.form-row').data('row');
    let formRow = $(this).closest('.form-row').prop('outerHTML');
    formRow = formRow.replaceAll('name[' + rowIndex + ']', 'name[' + index + ']').replaceAll('limit[' + rowIndex + ']', 'limit[' + index + ']').replaceAll('noLimit[' + rowIndex + ']', 'noLimit[' + index + ']').replaceAll('color[' + rowIndex + ']', 'color[' + index + ']');
    $(this).closest('.form-row').after(formRow);
    index++;
    if($('.form-row').length > 3) $('.removeRows').removeClass('opacity-0').removeAttr('disabled');
}
window.clickRemoveRows = function(event)
{
    $(this).closest('.form-row').remove();
    if($('.form-row').length <= 3) $('.removeRows').addClass('opacity-0').attr('disabled', true);
}

window.changeColumnLimit = function()
{
    const noLimit = $(this).prop('checked');
    if(noLimit)
    {
        $(this).closest('.form-row').find('[name^=limit]').val('').attr('disabled', true);
    }
    else
    {
        $(this).closest('.form-row').find('[name^=limit]').removeAttr('disabled');
    }
}

$(function()
{
    $('.removeRows').addClass('opacity-0').attr('disabled', true);
})
