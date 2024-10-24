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
}