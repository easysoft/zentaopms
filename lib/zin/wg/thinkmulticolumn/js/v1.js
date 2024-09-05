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
        title: $('.think-form-batch tbody tr').length >= canAddRowsOfMulticol ? addRowsTips.replace('%s', canAddRowsOfMulticol) : addLang,
    });
}

$(document).on('click', '.form-batch-row-actions .btn', function()
{
    renderRowData();
    if($(this).data('type') == 'add') $(this).closest('tr').next().find('td:first-child .form-control')[0].focus();
});

/**
 * 处理必填列变化。
 * Handle changes to required columns.
 *
 * @access public
 * @return void
 */
window.changeRequiredCols = function()
{
    const changedCols       = $('.required-options .picker-box').zui('picker').$.value;
    const quotedQuestions   = $('.required-options').data().quotedQuestions;
    const localRequiredCols = localStorage.getItem('requiredCols') ? localStorage.getItem('requiredCols').split(',') : null;
    const requiredCols      = localRequiredCols ? localRequiredCols : $('.required-options').data().requiredCols;
    const changedColsList   = changedCols.split(',');
    const diffCols          = requiredCols.filter(item => !changedColsList.includes(item));
    let selectColumn = [];
    let isQuoted     = false;
    let quotedIndex  = '';
}
