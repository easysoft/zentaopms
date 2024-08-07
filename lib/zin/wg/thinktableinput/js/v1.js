/**
 * 处理输入框值变化。
 * Handling input box value changes.
 *
 * @param   Event e Event object
 * @returns void
 */
window.changeInput = function(e)
{
    const value = $(this).val();
    const intValue = value < 1 ? 1 : parseInt(value, 10);
    $(this).val(intValue);
}

/**
 * 设置行号。
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
        $(this).find('[id^="customFields"]').attr('name', 'customFields[' + resultIndex + ']');
        $(this).find('[id^="result"]').attr('name', 'result[' + resultIndex + ']');
        $('.add-rows').toggleClass('disabled', index + 1 >= canAddRows);
        index ++;
    });
}

/**
 * 添加行。
 * Add rows.
 *
 * @param   event $e
 * @returns void
 */
window.addRow = function(e)
{
    if($('.rows-group').length >= canAddRows) return;
    const parentFormGroup = $(e.target || e).closest('.form-group');
    const $newRow = $('.rows-template').clone();
    $newRow.find('.btn-add').attr('onclick', 'addRow(this)');
    $newRow.removeClass('rows-template').removeClass('hidden').addClass('rows-group');
    $newRow.find('textarea').val('');
    parentFormGroup.after($newRow);
    $newRow.find('#customFields').trigger("focus");
    setLineIndex();
}

/**
 * 删除行。
 * Delete rows.
 *
 * @access public
 * @return void
 */
$('.think-step').on('click', '.btn-delete', function() {
    zui.Modal.confirm({message: deleteTip}).then((res) => {
        if(res)
        {
            const $row = $(this).closest('.form-group');
            $row.remove();
            if(canAddRows == 1) $('.add-rows').removeClass('disabled');
            setLineIndex();
        }
    })
})
