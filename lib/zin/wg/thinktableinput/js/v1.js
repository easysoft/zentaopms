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
    let index = 0;
    $('.rows-group').each(function()
    {
        const resultIndex = fieldsCount + index;
        $(this).find('[id^="customFields"]').attr('name', 'customFields[' + index + ']');
        $(this).find('[id^="result"]').attr('name', 'result[' + resultIndex + ']');
        $('.add-rows').toggleClass('disabled', index + 1 >= canAddRows);
        index ++;
    });
}

window.addRow = function(e)
{
    if($('.rows-group').length >= canAddRows) return;
    var parentFormGroup = $(e.target || e).closest('.form-group');
    var $newRow = $('.rows-template').clone();
    $newRow.find('.btn-add').attr('onclick', 'addRow(this)');
    $newRow.removeClass('rows-template').removeClass('hidden').addClass('rows-group');
    $newRow.find('textarea').val('');
    parentFormGroup.after($newRow);
    $newRow.find('#customFields').trigger("focus");
    setLineIndex();
}

$(document).bind('click', '.btn-delete', function() {
    zui.Modal.confirm({message: deleteTip}).then((res) => {
        if(res)
        {
            var $row = $(this).closest('.form-group');
            $row.remove();
            if(canAddRows == 1) $('.add-rows').removeClass('disabled');
            setLineIndex();
        }
    })
})
