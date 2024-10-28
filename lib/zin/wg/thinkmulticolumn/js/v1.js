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
 * 修改引用题目。
 * Change quote title.
 *
 * @access public
 * @return void
 */
window.changeQuoteTitle = function()
{
    let fields          = [];  //  所有的多列填空题的配置项
    let requiredFields  = [];  //  多列填空题必填的标题id
    let citationItems   = [];  //  选择列picker组件的下拉项
    let isMulticolumn   = false;
    let multicolumnStep = false

    const quoteQuestions = $('.options-quote-title').data('quoteQuestions');
    const quoteTitle     = $('input[name="options[quoteTitle]"]').val();
    const selectColumn   = $('.options-quote-title').data('selectColumn');
    const citation       = $('.quote-citation').data('citation');

    $.each(quoteQuestions, function(index, item) {
        const options = JSON.parse(item.options);
        if(quoteTitle && item.id == quoteTitle && options.questionType === 'multicolumn')
        {
            multicolumnStep = true;
            requiredFields  = options.requiredCols;
            fields          = options.fields;
        }
    });

    /* 渲染选择列的下拉项。Renders a drop-down item for a select column.*/
    $.each(requiredFields, function(index, key) {
        /* 必填列的值是多列填空fields数组的key。The value of in the required column is the key for the multi column fill in the blank fields array. */
        const keyValue = parseInt(key);
        citationItems.push({key: keyValue, value:keyValue, text: fields[keyValue - 1]});
    })

    $('.quote-citation').toggleClass('gap-4', multicolumnStep);
    $('.select-column').toggleClass('hidden', !multicolumnStep);
    $('.citation').toggleClass('hidden', multicolumnStep);
    $('.multicolumn-citation').toggleClass('hidden', !multicolumnStep);
    if(multicolumnStep)
    {
        /* 引用题目为多列填空题的时候，重置选择列的下拉选项和默认值， 修改引用方式为：引用某行输入项。 */
        /* When the reference question is a multi-column fill-in-the-blank question, reset the drop-down options and default values of the selected column, and change the reference method to: reference an input item in a row. */
        $('.select-column .picker-box').zui('picker').render({items: citationItems});
        $('.select-column .picker-box').zui('picker').$.setValue(selectColumn || citationItems[0]);
        $('.multicolumn-citation input[name="options[citation]"]').prop('checked', true);
    }
    else
    {
        /* 引用题目类型为多选或者单选题是，清空选择列的下拉选项和默认值，修改默认引用方式为：引用题的选中项。 */
        /* If the reference question type is multiple choice or single choice, clear the drop-down options and default values in the selection column, and change the default reference method to: reference the selected item in the question. */
        const defaultCitation = typeof citation != 'undefined' && citation < 3 ? citation : 1;
        $('.select-column .picker-box').zui('picker').render({items: []});
        $('.select-column .picker-box').zui('picker').$.setValue('');
        $('.citation input[id="options[citation]' + defaultCitation + '"]').prop('checked', true);
    }
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

/**
 * 修改选项配置。
 * Change option configuration.
 *
 * @param  target
 * @access public
 * @return void
 */
window.changeMulticolumnSetOption = function(target)
{
    $('.think-options-field').toggleClass('hidden', target.value == 1);
    $('.think-quote').toggleClass('hidden', target.value == 0);
    hiddenRequiredCols();
    $('.text-danger').remove();
    $('.has-error').removeClass('has-error');
    $('.required-tip button').toggleClass('hidden', target.value == 0);
}