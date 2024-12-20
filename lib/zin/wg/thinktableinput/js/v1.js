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
 * 处理输入框值变化。
 * Handling input box value changes.
 *
 * @param   Event e Event object
 * @returns void
 */
window.changePercentInput = function(e)
{
    const value    = $(e.target).val();
    const intValue = value ? parseInt(value, 10) : '';
    $(e.target).val(intValue);
}

/**
 * 输入框值限制只能输入小数点后两位的数字。
 * The input box value is limited to only two decimal places.
 *
 * @param   Event e Event object
 * @returns void
 */
window.changePriceInput = function(e)
{
    const value    = $(e.target).val();
    if(value.includes('.') && value.split('.')[1].length > 2)
    {
        $(this).val(value.slice(0, -1));
    }
}

/**
 * 设置行号。
 * Set line number.
 *
 * @param  number canAddRows
 * @param  number fieldsCount
 * @access public
 * @return void
 */
function setLineIndex(canAddRows, fieldsCount)
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
 * @access public
 * @returns void
 */
$(document).off('click', '.think-step .btn-add').on('click', '.think-step .btn-add', function()
{
    const canAddRows  = $(this).data('canAddRows');
    const fieldsCount = $(this).data('fieldsCount');
    if($('.rows-group').length >= canAddRows) return;

    const parentFormGroup = $(this).closest('.form-group');
    const $newRow = $('.rows-template').clone();
    $newRow.removeClass('rows-template').removeClass('hidden').addClass('rows-group');
    $newRow.find('textarea').val('');
    parentFormGroup.after($newRow);
    $newRow.find('#customFields').trigger("focus");
    setLineIndex(canAddRows, fieldsCount);
})

/**
 * 删除行。
 * Delete rows.
 *
 * @access public
 * @return void
 */
$(document).off('click', '.think-step .btn-delete').on('click', '.think-step .btn-delete', function()
{
    const canAddRows  = $(this).data('canAddRows');
    const fieldsCount = $(this).data('fieldsCount');
    zui.Modal.confirm({message: $(this).data('deleteTip')}).then((res) => {
        if(res)
        {
            const $row = $(this).closest('.form-group');
            $row.remove();
            if(canAddRows == 1) $('.add-rows').removeClass('disabled');
            setLineIndex(canAddRows, fieldsCount);
        }
    })
})
