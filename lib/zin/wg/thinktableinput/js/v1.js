window.changeSupportAdd = function(e)
{
    $('.can-add-rows').toggleClass('hidden', e.target.value == 0);
}
window.changeIsRequired = function(e)
{
    $('.required-rows').toggleClass('hidden', e.target.value == 0);
}
window.changeInput = function(e)
{
    const value = $(this).val();
    const intValue = value < 1 ? 1 : parseInt(value, 10);
    $(this).val(intValue);
}
/**
 * Set line number.
 *
 * @access public
 * @return void
 */
function setLineIndex()
{
    let index = 1;
    $('.rows-group').each(function()
    {
        const resultIndex = fieldsCount + index - 1;
        $(this).find('[id^="customFields"]').attr('name', 'customFields[' + index + ']');
        $(this).find('[id^="result"]').attr('name', 'result[' + resultIndex + ']');
        index ++;
    });
    $('.add-rows').toggleClass('disabled', index >= canAddRows);
}

window.addRow = function(e)
{
    if($('.rows-template').hasClass('hidden'))
    {
        $('.rows-template').removeClass('hidden');
    }
    else
    {
        if($('.rows-group').length >= canAddRows) return;
        var parentFormGroup = $(e.target || e).closest('.form-group');
        var $newRow = $('.rows-template').clone();
        $newRow.find('.btn-add').attr('onclick', 'addRow(this)');
        $newRow.removeClass('rows-template');
        $newRow.find('textarea').val('');
        parentFormGroup.after($newRow);
    }
    setLineIndex();
}

$(document).on('click', '.btn-delete', function() {
    var $row = $(this).closest('.form-group');
    $row.remove();
    setLineIndex();
})
