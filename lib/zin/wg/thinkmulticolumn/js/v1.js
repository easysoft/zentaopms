/**
 * 处理输入框值变化。
 * Handling input box value changes.
 *
 * @access public
 * @return void
 */
window.changeRows = function()
{
    const value = $(this).val();
    const intValue = value < 1 ? 1 : parseInt(value, 10);
    $(this).val(intValue);
}

window.renderRowData = function()
{
    $('.think-form-batch .form-batch-row-actions .btn[data-type="add"]').prop({
        disabled: $('.think-form-batch tbody tr').length >= canAddRowsOfMulticol,
        title: $('.think-form-batch tbody tr').length >= canAddRowsOfMulticol ? addRowsTips.replace('%s', canAddRowsOfMulticol) : '',
    });
}

$(document).on('click', '.form-batch-row-actions .btn', function(){renderRowData();});
