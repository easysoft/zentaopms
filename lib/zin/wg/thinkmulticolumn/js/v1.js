localStorage.removeItem('requiredCols');
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
        disabled: ($('.think-form-batch tbody tr').length >= canAddRowsOfMulticol || disabled),
        title: $('.think-form-batch tbody tr').length >= canAddRowsOfMulticol ? addRowsTips.replace('%s', canAddRowsOfMulticol) : addLang,
    });
    $('.think-form-batch .form-batch-row-actions .btn[data-type="delete"]').prop({
        disabled: disabled,
    })

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
    const diffCols          = requiredCols ? requiredCols.filter(item => !changedColsList.includes(item)) : [];

    let selectColumn = [];
    let isQuoted     = false;
    let quotedIndex  = '';

    /* 判断删除的比填列是不是被其他问题引用的列。 Determine whether the deleted column is a column referenced by other issues.*/
    quotedQuestions.forEach(function(item)
    {
        const itemOptions = JSON.parse(item.options);
        selectColumn.push(itemOptions.selectColumn);
        if(diffCols.length > 0 && itemOptions.selectColumn == diffCols[0])
        {
            quotedIndex = quotedIndex + (!isQuoted ? '' : '、') + tipQuestion.replace(/%s/g, item.index) ;
            isQuoted    = true;
        }
    });
    /* 如果删除的比填列是被其他问题引用的列则不能删除，并弹出弹窗提示；如果不是被其他问题引用的列则成功删除，并且储存删除后的值。*/
    /* If the deleted column is a column referenced by other issues, it cannot be deleted and a pop-up prompt will appear; If the column is not referenced by other issues, it will be successfully deleted and the deleted value will be saved. */
    if(isQuoted)
    {
        $('.required-options .picker-box').zui('picker').$.setValue(requiredCols);
        zui.Modal.alert({message: requiredColTip.replace(/%s/g, quotedIndex), icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x', size: 'sm'});
    }
    if(!isQuoted) localStorage.setItem('requiredCols', changedCols);
}

/**
 * 隐藏必填列。
 * Hide required columns..
 *
 * @access public
 * @return void
 */
window.hiddenRequiredCols = function()
{
    const requiredValue   = $('input[name="options[required]"]:checked').val();
    const setOptionsValue = $('input[name="options[setOption]"]:checked').val();
    $('.required-options').toggleClass('hidden', setOptionsValue == '1' || (setOptionsValue == '0' && requiredValue == '0'))
}